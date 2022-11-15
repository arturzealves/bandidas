<?php

namespace Tests\Feature;

use App\Http\Controllers\SpotifyController;
use App\Http\Livewire\Spotify\SpotifyRegisterForm;
use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Models\User;
use App\Models\UserExternalAccount;
use App\Providers\RouteServiceProvider;
use App\Services\SpotifyService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as ContractsUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Livewire\Livewire;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class SpotifyIntegrationTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;

    public function test_redirect()
    {
        $testRedirectUrl = 'http://spotify.test';

        $spotifyProvider = $this->prophesize(AbstractProvider::class);
        Socialite::shouldReceive('driver')
            ->once()
            ->with('spotify')
            ->andReturn($spotifyProvider->reveal());

        $spotifyProvider->scopes([
            SpotifyService::SCOPE_USER_READ_EMAIL,
            SpotifyService::SCOPE_USER_FOLLOW_READ,
        ])
            ->shouldBeCalledTimes(1)
            ->willReturn($spotifyProvider->reveal());
        
        $redirectResponse = new RedirectResponse($testRedirectUrl);
        $spotifyProvider->redirect()
            ->shouldBeCalledTimes(1)
            ->willReturn($redirectResponse);

        $response = $this->get('/auth/spotify/redirect/login');

        $response->assertStatus(302);
        $response->assertRedirectContains($testRedirectUrl);
    }

    public function test_spotify_register_form_is_successful()
    {
        $user = User::factory()->create();
        Livewire::actingAs($user);
        
        Livewire::test(SpotifyRegisterForm::class)
            ->call('authenticate')
            ->assertViewIs('livewire.spotify.spotify-register-form')
            ->assertSee('Register with Spotify');
    }

    public function test_is_successful_on_login_with_existing_user_who_registered_with_spotify_account()
    {
        $this->seed();

        $user = User::factory()->create();

        $spotifyUser = $this->mockInitialSetup($user);

        UserExternalAccount::factory()->create([
            'user_id' => $user->id,
            'external_id' => $spotifyUser->id,
            'provider_name' => UserExternalAccount::PROVIDER_SPOTIFY
        ]);

        Auth::shouldReceive('user')->once()->andReturn(null);
        Auth::shouldReceive('login')->once();

        $this->assertDatabaseCount('user_access_tokens', 0);
        
        $response = $this
            ->withSession([SpotifyController::REDIRECT_ACTION => SpotifyController::REDIRECT_ACTION_LOGIN])
            ->get('/auth/spotify/callback?code=testCode&state=testState');

        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertDatabaseCount('user_access_tokens', 1);
        $this->assertDatabaseCount('user_external_accounts', 1);
    }

    public function test_is_successful_on_get_followed_artists_with_existing_user_who_registered_without_spotify_account()
    {
        $this->seed();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mockInitialSetup($user);

        Bus::fake([ImportSpotifyUserFollowedArtists::class]);
        
        $this->assertDatabaseCount('user_access_tokens', 0);
        $this->assertDatabaseCount('user_external_accounts', 0);
        
        $response = $this
            ->withSession([SpotifyController::REDIRECT_ACTION => SpotifyController::REDIRECT_ACTION_GET_FOLLOWED_ARTISTS])
            ->get('/auth/spotify/callback?code=testCode&state=testState');
            
        Bus::assertDispatched(ImportSpotifyUserFollowedArtists::class);

        $response->assertStatus(302);
        $response->assertRedirect('/artists');
        $this->assertDatabaseCount('user_access_tokens', 1);
        $this->assertDatabaseCount('user_external_accounts', 1);
    }

    public function test_redirects_when_unregistered_user_tries_to_login()
    {
        $this->seed();

        $this->mockInitialSetup();

        $response = $this
            ->withSession([SpotifyController::REDIRECT_ACTION => SpotifyController::REDIRECT_ACTION_LOGIN])
            ->get('/auth/spotify/callback?code=testCode&state=testState');

        $this->assertDatabaseCount('user_access_tokens', 0);
        $this->assertDatabaseCount('user_external_accounts', 0);
        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['msg']);
    }

    public function test_is_successful_with_new_user_registration()
    {
        $this->seed();

        $this->mockInitialSetup();

        $this->assertDatabaseCount('user_access_tokens', 0);
        $this->assertDatabaseCount('user_external_accounts', 0);
        
        Event::fake([Registered::class]);
        
        $response = $this
            ->withSession([SpotifyController::REDIRECT_ACTION => SpotifyController::REDIRECT_ACTION_REGISTER])
            ->get('/auth/spotify/callback?code=testCode&state=testState');

        Event::assertDispatched(Registered::class);
        $this->assertDatabaseCount('user_access_tokens', 1);
        $this->assertDatabaseCount('user_external_accounts', 1);
        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    private function mockInitialSetup($user = null)
    {
        $spotifyProvider = $this->prophesize(Provider::class);
        Socialite::shouldReceive('driver')
            ->once()
            ->with('spotify')
            ->andReturn($spotifyProvider->reveal());

        $spotifyUser = $this->prophesize(ContractsUser::class);
        $spotifyProvider->user()->shouldBeCalledTimes(1)->willReturn($spotifyUser->reveal());

        $spotifyUser->id = fake()->md5();
        $spotifyUser->name = $user ? $user->name : 'testName';
        $spotifyUser->email = $user ? $user->email : 'testemail@test.com';
        $spotifyUser->accessTokenResponseBody = [
            'access_token' => 'test-access-token',
            'refresh_token' => 'test-refresh-token',
            'scope' => 'scope1 scope2',
            'expires_in' => '120',
        ];

        return $spotifyUser;
    }
}
