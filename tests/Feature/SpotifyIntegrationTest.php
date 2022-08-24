<?php

namespace Tests\Feature;

use App\Http\Livewire\Spotify\SpotifyGetUserFollowedArtists;
use App\Http\Livewire\Spotify\SpotifyRegisterForm;
use App\Models\User;
use App\Models\UserExternalAccount;
use App\Services\SpotifyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
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

    public function test_authenticate_redirects_to_spotify()
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

    public function test_spotify_get_user_followed_artists_event_is_dispatched_successfully()
    {
        Livewire::actingAs(User::factory()->create());

        Bus::fake();

        Livewire::test(SpotifyGetUserFollowedArtists::class)
            ->call('getUserFollowedArtists')
            ->assertViewIs('livewire.spotify.spotify-get-user-followed-artists');
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

    public function test_spotify_controller_callback_is_successful()
    {
        $this->seed();

        $code = 'testCode';
        $state = 'testState';

        $user = User::factory()->create();
        $this->actingAs($user);

        $spotifyProvider = $this->prophesize(Provider::class);
        Socialite::shouldReceive('driver')
            ->once()
            ->with('spotify')
            ->andReturn($spotifyProvider->reveal());

        $spotifyUser = $this->prophesize(ContractsUser::class);
        $spotifyProvider->user()->shouldBeCalledTimes(1)->willReturn($spotifyUser->reveal());

        $spotifyUser->id = fake()->md5();
        $spotifyUser->name = $user->name;
        $spotifyUser->email = $user->email;
        $spotifyUser->accessTokenResponseBody = [
            'access_token' => 'test-access-token',
            'refresh_token' => 'test-refresh-token',
            'scope' => 'scope1 scope2',
            'expires_in' => '120',
        ];

        UserExternalAccount::factory()->create([
            'user_id' => $user->id,
            'external_id' => $spotifyUser->id,
            'provider_name' => UserExternalAccount::PROVIDER_SPOTIFY
        ]);

        Auth::shouldReceive('login')->once();

        $this->assertDatabaseCount('user_access_tokens', 0);
        
        $response = $this->get(sprintf('/auth/spotify/callback?code=%s&state=%s', $code, $state));

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseCount('user_access_tokens', 1);
    }
}
