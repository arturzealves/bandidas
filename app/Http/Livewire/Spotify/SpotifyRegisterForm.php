<?php

namespace App\Http\Livewire\Spotify;

use App\Services\SpotifyService;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class SpotifyRegisterForm extends Component
{
    public function authenticate(SpotifyService $spotifyService)
    {
        $url = $spotifyService->authenticate();
        
        return Redirect::to($url);
    }

    public function render()
    {
        return view('livewire.spotify.spotify-register-form');
    }
}
