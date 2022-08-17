<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Models\ArtistHasGenre;
use App\Models\Genre;
use App\Models\SpotifyArtist;
use App\Models\User;
use App\Models\UserFollowsArtist;
use App\Services\SpotifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportSpotifyUserFollowedArtists implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $spotifyService;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SpotifyService $spotifyService, User $user)
    {
        $this->spotifyService = $spotifyService;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $artists = $this->spotifyService->getUserFollowedArtists($this->user);
        foreach ($artists as $artistData) {
            $artist = Artist::firstOrCreate(['name' => $artistData->name]);

            UserFollowsArtist::firstOrCreate([
                'user_id' => $this->user->id,
                'artist_id' => $artist->id,
            ]);

            SpotifyArtist::firstOrCreate(
                [
                    'spotify_id' => $artistData->id
                ],
                [
                    'artist_id' => $artist->id,
                    'name' => $artistData->name,
                    'uri' => $artistData->uri,
                    'images' => json_encode($artistData->images),
                    'href' => $artistData->href,
                    'followers' => $artistData->followers->total,
                    'popularity' => $artistData->popularity,
                    'external_urls' => json_encode($artistData->external_urls),
                ]
            );

            foreach ($artistData->genres as $name) {
                $genre = Genre::firstOrCreate(['name' => $name]);
                
                ArtistHasGenre::firstOrCreate([
                    'artist_id' => $artist->id,
                    'genre_id' => $genre->id,
                ]);
            }
        }
    }
}
