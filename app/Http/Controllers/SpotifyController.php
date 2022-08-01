<?php

namespace App\Http\Controllers;

use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SpotifyController extends Controller
{
    public function auth(SpotifyService $spotifyService)
    {
        $url = $spotifyService->authenticate();
        
        return Redirect::to($url);
    }

    public function callback(Request $request, SpotifyService $spotifyService)
    {
        $spotifyService->callback(Auth::user(), $request->code, $request->state);

        return Redirect::to('spotify');
    }

    public function spotify(SpotifyService $spotifyService)
    {
        ImportSpotifyUserFollowedArtists::dispatch($spotifyService, Auth::user());
    }
}
