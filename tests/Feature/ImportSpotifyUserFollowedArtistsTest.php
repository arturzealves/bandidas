<?php

namespace Tests\Feature;

use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Models\User;
use App\Services\SpotifyService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Prophecy\PhpUnit\ProphecyTrait;
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

    public function test_handle_catch_exception()
    {
        $this->spotifyService
            ->getUserFollowedArtists($this->user)
            ->shouldBeCalledTimes(1)
            ->willThrow(Exception::class);

        $this->job->handle();
    }

    public function test_handle_is_successful()
    {
        $artists = [
            [
                'id' => 1,
                'name' => 'Artist 1',
            ]
        ];

        $this->spotifyService
            ->getUserFollowedArtists($this->user)
            ->shouldBeCalledTimes(1)
            ->willReturn($artists);

        $this->job->handle();
    }
}
