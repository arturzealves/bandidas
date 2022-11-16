<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Repositories\ArtistRepository;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index(ArtistRepository $artistRepository)
    {
        return view('artists.index')
            ->with([
                'artists' => $artistRepository->getAllSpotifyArtistsWithImages(),
            ])
        ;
    }

    public function show(Artist $artist)
    {
        if ($artist->spotify) {
            $artist->largeImage = $artist->spotify->getBiggerImage();
            $artist->mediumImage = $artist->spotify->getMediumImage();
            $artist->spotify->url = json_decode($artist->spotify->external_urls)->spotify;
        }

        return view('artists.show')->with(['artist' => $artist]);
    }
}
