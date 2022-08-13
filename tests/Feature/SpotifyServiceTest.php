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

    const SPOTIFY_ID = 'id';
    const SPOTIFY_SECRET = 'secret';
    const SPOTIFY_REDIRECT = '<-back<-';
    const SPOTIFY_STATE = 'state';
    const SPOTIFY_CODE = 'code';

    private $service;
    private $repository;
    private $api;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->prophesize(UserAccessTokenRepository::class);
        $this->api = $this->prophesize(SpotifyWebAPI::class);
        
        $this->service = new SpotifyService($this->repository->reveal(), $this->api->reveal());
    }

    public function test_authenticate()
    {
        $expected = 'authenticated with success';

        $session = $this->mockGetSpotifySession();
        $session->generateState()
            ->shouldBeCalledTimes(1)
            ->willReturn(self::SPOTIFY_STATE);

        Cache::shouldReceive('put')
            ->once()
            ->withArgs(['spotifyState', self::SPOTIFY_STATE]);
    
        $options = [
            'scope' => [
                'user-read-private',
                'user-read-email',
                'user-follow-read',
            ],
            'show_dialog' => true,
            'state' => self::SPOTIFY_STATE,
        ];
    
        $session->getAuthorizeUrl($options)
            ->shouldBeCalledTimes(1)
            ->willReturn($expected);
        
        $result = $this->service->authenticate($session->reveal());
        $this->assertIsString($result);
        $this->assertEquals($expected, $result);
    }

    public function test_callback_throws_invalid_state_exception()
    {
        $state = 'invalid state';
        $session = $this->mockGetSpotifySession();

        Cache::shouldReceive('get')
            ->once()
            ->with('spotifyState')
            ->andReturn(self::SPOTIFY_STATE);

        $user = $this->prophesize(User::class);

        $this->expectException(SpotifyInvalidStateException::class);

        $this->service->callback($session->reveal(), $user->reveal(), self::SPOTIFY_CODE, $state);
    }

    public function test_callback_throws_invalid_access_token_exception()
    {
        $session = $this->mockGetSpotifySession();

        Cache::shouldReceive('get')
            ->once()
            ->with('spotifyState')
            ->andReturn(self::SPOTIFY_STATE);

        $session->requestAccessToken(self::SPOTIFY_CODE)
            ->shouldBeCalledTimes(1)
            ->willReturn(false);

        $user = $this->prophesize(User::class);

        $this->expectException(SpotifyAccessTokenException::class);

        $this->service->callback($session->reveal(), $user->reveal(), self::SPOTIFY_CODE, self::SPOTIFY_STATE);
    }

    // public function test_callback_is_successful()
    // {
    //     $session = $this->mockGetSpotifySession();

    //     Cache::shouldReceive('get')
    //         ->once()
    //         ->with('spotifyState')
    //         ->andReturn(self::SPOTIFY_STATE);

    //     $session->requestAccessToken(self::SPOTIFY_CODE)
    //         ->shouldBeCalledTimes(1)
    //         ->willReturn(true);

    //     $user = User::factory()->create();

    //     $this->assertDatabaseCount('user_access_token', 1);
        
    //     $this->service->callback($session->reveal(), $user, self::SPOTIFY_CODE, self::SPOTIFY_STATE);
    // }

    public function test_get_user_followed_artists_throws_invalid_access_token_exception()
    {
        $user = User::factory()->create();

        $this->repository->getOneByUserIdAndTokenableId(
            $user->id,
            UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(null);

        $this->expectException(UserAccessTokenNotFoundException::class);

        $this->service->getUserFollowedArtists($user);
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
            ->with($key)
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
            ->with($key)
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
            ->with($key)
            ->andReturn(false);
        
        $this->api->setAccessToken($userAccessToken->token)
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
            ->with($key, $response, 604800)
            ->andReturn(true);

        $this->assertEquals(
            [ $artistOne, $artistTwo ],
            $this->service->getUserFollowedArtists($user)
        );
    }

    protected function mockGetSpotifySession()
    {
        Config::shouldReceive('get')
            ->with('services.spotify.id')
            ->andReturn(self::SPOTIFY_ID);
        
        Config::shouldReceive('get')
            ->with('services.spotify.secret')
            ->andReturn(self::SPOTIFY_SECRET);
        
        Config::shouldReceive('get')
            ->with('services.spotify.redirect')
            ->andReturn(self::SPOTIFY_REDIRECT);

        $session = $this->prophesize(Session::class);

        $session->setClientId(self::SPOTIFY_ID);
        $session->setClientSecret(self::SPOTIFY_SECRET);
        $session->setRedirectUri(self::SPOTIFY_REDIRECT);
        
        return $session;
    }
}
