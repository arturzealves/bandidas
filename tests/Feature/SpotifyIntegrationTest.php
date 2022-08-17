<?php

namespace Tests\Feature;

use App\Http\Livewire\Spotify\SpotifyGetUserFollowedArtists;
use App\Http\Livewire\Spotify\SpotifyRegisterCallbackForm;
use App\Http\Livewire\Spotify\SpotifyRegisterForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class SpotifyIntegrationTest extends TestCase
{
    public function test_authenticate_redirects_to_spotify()
    {
        $this->actingAs($user = User::factory()->create());
        
        $response = $this->get('/spotify/auth');

        $response->assertStatus(302);
        $response->assertRedirectContains('https://accounts.spotify.com/authorize');
    }

    public function test_spotify_get_user_followed_artists_event_is_dispatched_successfully()
    {
        Livewire::actingAs(User::factory()->create());

        Bus::fake();

        Livewire::test(SpotifyGetUserFollowedArtists::class)
            ->call('getUserFollowedArtists')
            ->assertViewIs('livewire.spotify.spotify-get-user-followed-artists');
    }

    public function test_spotify_register_callback_form_render_is_successful()
    {
        $user = User::factory()->create();
        Livewire::actingAs($user);

        Livewire::test(SpotifyRegisterCallbackForm::class)
            ->set('name', $user->name)
            ->set('email', $user->email)
            ->call('render')
            ->assertViewIs('livewire.spotify.spotify-register-callback-form')
            ->assertSee($user->name)
            ->assertSee($user->email);
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

    public function test_spotify_controller_callback_catches_exception_and_redirects_to_dashboard()
    {
        $code = 'testCode';
        $state = 'testState';

        $this->actingAs(User::factory()->create());

        Cache::shouldReceive('get')
            ->once()
            ->withArgs(['spotifyState'])
            ->andReturn($state);

        $response = $this->get(sprintf('/spotify/callback?code=%s&state=%s', $code, $state));

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }
}
