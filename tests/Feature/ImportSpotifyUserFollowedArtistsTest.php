<?php

namespace Tests\Feature;

use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Models\User;
use App\Services\SpotifyService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Prophecy\PhpUnit\ProphecyTrait;
use stdClass;
use Tests\TestCase;

class ImportSpotifyUserFollowedArtistsTest extends TestCase
{
    use ProphecyTrait;
    
    private $spotifyService;
    private $user;
    private $job;

    public function setUp(): void
    {
        parent::setUp();

        $this->spotifyService = $this->prophesize(SpotifyService::class);
        $this->user = User::factory()->create();

        $this->job = new ImportSpotifyUserFollowedArtists($this->spotifyService->reveal(), $this->user);
    }

    public function test_handle_is_successful()
    {
        $artist1 = new stdClass();
        $artist1->id = 1;
        $artist1->name = 'Artist 1';
        $artist1->uri = 'testUri';
        $artist1->images = [];
        $artist1->href = 'testHref';
        $artist1->followers = new stdClass();
        $artist1->followers->total = 1;
        $artist1->popularity = 1;
        $artist1->external_urls = 'external_urls';
        $artist1->genres = ['genre1', 'genre2', 'genre3'];

        $artists = [$artist1];

        $this->spotifyService
            ->getUserFollowedArtists($this->user)
            ->shouldBeCalledTimes(1)
            ->willReturn($artists);

        $this->job->handle();
    }
}
