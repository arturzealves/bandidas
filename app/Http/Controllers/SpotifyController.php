<?php

namespace App\Http\Controllers;

use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use SpotifyWebAPI\SpotifyWebAPIAuthException;
use SpotifyWebAPI\SpotifyWebAPIException;

class SpotifyController extends Controller
{
    public function auth(SpotifyService $spotifyService)
    {
        $url = $spotifyService->authenticate();

        return Redirect::to($url);
    }

    public function callback(
        Request $request,
        SpotifyService $spotifyService
    ) {
        try {
            $spotifyService->validateCallback($request->code, $request->state);
        } catch (SpotifyWebAPIException $e) {
            return Redirect::to('dashboard');
        } catch (SpotifyWebAPIAuthException $e) {
            return Redirect::to('dashboard');
        }
        
        $user = Auth::user();
        if ($user) {
            $spotifyService->saveUserAccessToken(Auth::user());
            
            return Redirect::to('dashboard');
        }

        $response = $spotifyService->getUserProfile();
        Session::put('spotify-register-name', $response->display_name);
        Session::put('spotify-register-email', $response->email);

        return Redirect::to('/spotify/register');
    }
}
