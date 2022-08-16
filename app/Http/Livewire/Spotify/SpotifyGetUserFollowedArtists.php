<?php

namespace App\Http\Livewire\Spotify;

use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Services\SpotifyService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use SpotifyWebAPI\Session;

class SpotifyGetUserFollowedArtists extends Component
{
    public function getUserFollowedArtists(SpotifyService $service, Session $session)
    {
        ImportSpotifyUserFollowedArtists::dispatch($service, Auth::user());
    }
    
    public function render()
    {
        return view('livewire.spotify.spotify-get-user-followed-artists');
    }
}
