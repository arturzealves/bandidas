<?php

namespace App\Http\Controllers;

use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use SpotifyWebAPI\Session;

class SpotifyController extends Controller
{
    public function auth(SpotifyService $spotifyService, Session $session)
    {
        $url = $spotifyService->authenticate($session);
        
        return Redirect::to($url);
    }

    public function callback(Request $request, SpotifyService $spotifyService, Session $session)
    {
        $spotifyService->callback($session, Auth::user(), $request->code, $request->state);

        return Redirect::to('spotify');
    }

    public function spotify(SpotifyService $spotifyService)
    {
        ImportSpotifyUserFollowedArtists::dispatch($spotifyService, Auth::user());
    }
}
