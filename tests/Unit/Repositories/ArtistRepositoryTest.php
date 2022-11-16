<?php

namespace Tests\Unit\Repositories;

use App\Models\Artist;
use App\Models\SpotifyArtist;
use App\Models\User;
use App\Repositories\ArtistRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArtistRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new ArtistRepository();
    }

    public function testGetAllSpotifyArtistsWithImages()
    {
        SpotifyArtist::factory(5)->create();
        SpotifyArtist::factory()->create(['images' => json_encode([])]);

        $result = $this->repository->getAllSpotifyArtistsWithImages();

        $this->assertEquals(6, $result->count());
    }

    public function testGetRandomSpotifyArtistsWithImages()
    {
        SpotifyArtist::factory(3)->create();
        SpotifyArtist::factory(3)->create(['images' => json_encode([])]);

        $result = $this->repository->getRandomSpotifyArtistsWithImages(5);

        $this->assertEquals(5, $result->count());
    }
}
