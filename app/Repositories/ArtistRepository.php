<?php

namespace App\Repositories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Collection;

class ArtistRepository
{
    public function getAllSpotifyArtistsWithImages(): Collection
    {
        return Artist::join('spotify_artists', 'spotify_artists.artist_uuid', '=', 'artists.uuid')
            ->get()
            ->map(function ($artist) {
                if (!$artist->images) {
                    return $artist;
                }

                $imagesArray = json_decode($artist->images);
                $artist->largeImage = $imagesArray[0];
                $artist->mediumImage = $imagesArray[1];
                $artist->smallImage = $imagesArray[2];

                return $artist;
            })
        ;
    }

    public function getRandomSpotifyArtistsWithImages($total = 12): Collection
    {
        return Artist::join('spotify_artists', 'spotify_artists.artist_uuid', '=', 'artists.uuid')
            ->limit($total)
            ->inRandomOrder()
            ->get()
            ->map(function ($artist) {
                if (!$artist->images) {
                    return $artist;
                }

                $imagesArray = json_decode($artist->images);
                $artist->largeImage = $imagesArray[0];
                $artist->mediumImage = $imagesArray[1];
                $artist->smallImage = $imagesArray[2];

                return $artist;
            })
        ;
    }
}
