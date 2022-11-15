<?php

namespace Tests\Feature\Controllers;

use App\Models\SpotifyArtist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArtistsTest extends TestCase
{
    public function test_artists_list_page_is_visible()
    {
        $this->actingAs(User::factory()->create());
        
        $response = $this->get('/artists');

        $response->assertStatus(200);
        $response->assertSee('Artists');
    }

    public function test_artist_show_page_is_visible()
    {
        $spotifyArtist = SpotifyArtist::factory()->create();
    
        $response = $this->get(sprintf('/artists/%s', $spotifyArtist->artist->uuid));

        $response->assertStatus(200)
            ->assertSee($spotifyArtist->artist->name);
    }
}
