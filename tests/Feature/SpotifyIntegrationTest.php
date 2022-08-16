<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
