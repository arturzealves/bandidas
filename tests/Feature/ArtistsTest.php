<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArtistsTest extends TestCase
{
    public function test_artists_list_page_is_visible()
    {
        $this->actingAs($user = User::factory()->create());
        
        $response = $this->get('/artists');

        $response->assertStatus(200);
        $response->assertSee('Artists');
    }

    public function test_artist_show_page_is_visible()
    {
        $this->actingAs($user = User::factory()->create());

        $artist = Artist::factory()->create();
    
        $response = $this->get(sprintf('/artists/%s', $artist->uuid));

        $response->assertStatus(200);
        $response->assertSee($artist->name);
    }
}
