<?php

namespace App\Http\Livewire\Spotify;

use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class SpotifyRegisterForm extends Component
{
    public $url;

    public function authenticate()
    {
        return Redirect::route(
            'spotify.redirect', 
            ['action' => SpotifyController::REDIRECT_ACTION_REGISTER]
        );
    }

    public function render()
    {
        return view('livewire.spotify.spotify-register-form');
    }
}
