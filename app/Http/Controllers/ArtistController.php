<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::with('spotify')
            ->get()
            ->map(function($artist) {
                if (!$artist->spotify) {
                    return $artist;
                }
                $artist->smallImage = $artist->spotify->getSmallerImage();

                return $artist;
            });

        return view('artists.index')
            ->with([
                'artists' => $artists,
            ]);
    }

    public function show(Artist $artist)
    {
        if ($artist->spotify) {
            $artist->mediumImage = $artist->spotify->getMediumImage();
            $artist->spotify->url = json_decode($artist->spotify->external_urls)->spotify;
        }

        return view('artists.show')->with(['artist' => $artist]);
    }
}
