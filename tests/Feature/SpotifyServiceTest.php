<?php

namespace Tests\Feature;

use App\Exceptions\SpotifyAccessTokenException;
use App\Exceptions\SpotifyInvalidStateException;
use App\Exceptions\UserAccessTokenNotFoundException;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Repositories\UserAccessTokenRepository;
use App\Services\SpotifyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Prophecy\PhpUnit\ProphecyTrait;
use Illuminate\Support\Facades\Config;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Tests\TestCase;

class SpotifyServiceTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;

    const SPOTIFY_ID = 'id';
    const SPOTIFY_SECRET = 'secret';
    const SPOTIFY_REDIRECT = '<-back<-';
    const SPOTIFY_STATE = 'state';
    const SPOTIFY_CODE = 'code';
    const SPOTIFY_ACCESS_TOKEN = 'testAccessToken';
    const SPOTIFY_REFRESH_TOKEN = 'testRefreshToken';

    private $service;
    private $api;
    private $session;
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->session = $this->prophesize(Session::class);
        $this->session->setClientId(self::SPOTIFY_ID);
        $this->session->setClientSecret(self::SPOTIFY_SECRET);
        $this->session->setRedirectUri(self::SPOTIFY_REDIRECT);

        $this->api = $this->prophesize(SpotifyWebAPI::class);
        $this->api->setSession($this->session->reveal())
            ->shouldBeCalledTimes(1);

        $this->repository = $this->prophesize(UserAccessTokenRepository::class);
        
        $this->service = new SpotifyService(
            $this->api->reveal(),
            $this->session->reveal(),
            $this->repository->reveal()
        );
    }

    public function test_authenticate()
    {
        $expected = 'authenticated with success';

        $this->session->generateState()
            ->shouldBeCalledTimes(1)
            ->willReturn(self::SPOTIFY_STATE);

        Cache::shouldReceive('put')
            ->once()
            ->withArgs(['spotifyState', self::SPOTIFY_STATE]);
    
        $options = [
            'scope' => [
                SpotifyService::SCOPE_USER_READ_EMAIL,
                SpotifyService::SCOPE_USER_FOLLOW_READ,
            ],
            'show_dialog' => false,
            'state' => self::SPOTIFY_STATE,
        ];
    
        $this->session->getAuthorizeUrl($options)
            ->shouldBeCalledTimes(1)
            ->willReturn($expected);
        
        $result = $this->service->authenticate();
        $this->assertIsString($result);
        $this->assertEquals($expected, $result);
    }

    public function test_validate_callback_throws_invalid_state_exception()
    {
        $state = 'invalid state';

        Cache::shouldReceive('get')
            ->once()
            ->withArgs(['spotifyState'])
            ->andReturn(self::SPOTIFY_STATE);

        $this->expectException(SpotifyInvalidStateException::class);

        $this->service->validateCallback(self::SPOTIFY_CODE, $state);
    }

    public function test_callback_throws_invalid_access_token_exception()
    {
        Cache::shouldReceive('get')
            ->once()
            ->withArgs(['spotifyState'])
            ->andReturn(self::SPOTIFY_STATE);

        $this->session->requestAccessToken(self::SPOTIFY_CODE)
            ->shouldBeCalledTimes(1)
            ->willReturn(false);

        $user = $this->prophesize(User::class);

        $this->expectException(SpotifyAccessTokenException::class);

        $this->service->validateCallback(self::SPOTIFY_CODE, self::SPOTIFY_STATE);
    }

    public function test_get_session()
    {
        $this->assertEquals($this->session->reveal(), $this->service->getSession());
    }

    public function test_get_user_profile()
    {
        $expected = '';
        $accessToken = 'test access token';

        $this->session->getAccessToken()
            ->shouldBeCalledTimes(1)
            ->willReturn($accessToken);

        $this->api->setAccessToken($accessToken)
            ->shouldBeCalledTimes(1);
        
        $this->api->me()
            ->shouldBeCalledTimes(1)
            ->willReturn($expected);
        
        $result = $this->service->getUserProfile();
        $this->assertEquals($expected, $result);
    }

    public function test_saveUserAccessToken_is_successful()
    {
        $this->session->getAccessToken()
            ->shouldBeCalledTimes(1)
            ->willReturn(self::SPOTIFY_ACCESS_TOKEN);

        $this->session->getRefreshToken()
            ->shouldBeCalledTimes(1)
            ->willReturn(self::SPOTIFY_REFRESH_TOKEN);

        $this->session->getScope()
            ->shouldBeCalledTimes(1)
            ->willReturn(['testScope']);

        $this->session->getTokenExpiration()
            ->shouldBeCalledTimes(1)
            ->willReturn(123);

        $user = User::factory()->create();

        $this->assertDatabaseCount('user_access_tokens', 0);
        $this->service->saveUserAccessToken($user);
        $this->assertDatabaseCount('user_access_tokens', 1);
    }

    public function test_get_user_followed_artists_throws_invalid_access_token_exception()
    {
        $user = $this->prophesize(User::class);

        $this->repository->getOneByUserIdAndTokenableId(
            $user->id,
            UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(null);

        $this->expectException(UserAccessTokenNotFoundException::class);

        $this->service->getUserFollowedArtists($user->reveal());
    }

    public function test_get_user_followed_artists_is_successful()
    {
        $userAccessToken = UserAccessToken::factory()->create();
        $user = $userAccessToken->user;

        $this->repository->getOneByUserIdAndTokenableId(
            $user->id,
            UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN
        )
            ->shouldBeCalledTimes(1)
            ->willReturn($userAccessToken);
        
        // First loop iteration, getting from Cache
        $options = ['limit' => 50];
        $key = sprintf(
            'spotify_user_%s_following_%s',
            $user->id,
            hash('sha256', json_encode($options))
        );

        Cache::shouldReceive('has')
            ->once()
            ->withArgs([$key])
            ->andReturn(true);

        $artistOne = [
            'artist' => fake()->name(),
        ];
        $response = new \stdClass();
        $response->artists = new \stdClass();
        $response->artists->items = [ $artistOne ];
        $response->artists->next = 'not empty';
        $response->artists->cursors = new \stdClass();
        $response->artists->cursors->after = 'test';

        Cache::shouldReceive('get')
            ->once()
            ->withArgs([$key])
            ->andReturn($response);

        // Seconds loop iteration, requesting from API
        $options = ['limit' => 50, 'after' => 'test'];
        $key = sprintf(
            'spotify_user_%s_following_%s',
            $user->id,
            hash('sha256', json_encode($options))
        );

        Cache::shouldReceive('has')
            ->once()
            ->withArgs([$key])
            ->andReturn(false);
        
        // $this->api->setAccessToken($userAccessToken->token)
        //     ->shouldBeCalledTimes(1);
        $this->session->refreshAccessToken($userAccessToken->refresh_token)
            ->shouldBeCalledTimes(1);
        
        $artistTwo = [
            'artist' => fake()->name(),
        ];
        $response = new \stdClass();
        $response->artists = new \stdClass();
        $response->artists->items = [ $artistTwo ];
        $response->artists->next = '';
        $response->artists->cursors = new \stdClass();
        $response->artists->cursors->after = '';

        $this->api->getUserFollowedArtists($options)
            ->shouldBeCalledTimes(1)
            ->willReturn($response);

        Cache::shouldReceive('put')
            ->once()
            ->withArgs([$key, $response, 604800])
            ->andReturn(true);

        $this->assertEquals(
            [ $artistOne, $artistTwo ],
            $this->service->getUserFollowedArtists($user)
        );
    }
}
