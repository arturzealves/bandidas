<?php

namespace App\Http\Livewire\Spotify;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SpotifyRegisterCallbackForm extends Component
{
    public $name;
    public $email;

    public function mount() {
        $this->name = Session::get('spotify-register-name');
        $this->email = Session::get('spotify-register-email');
    }
    
    public function render()
    {
        return view('livewire.spotify.spotify-register-callback-form')
            ->layout('layouts.guest')
            ->with([
                'name' => $this->name,
                'email' => $this->email
            ]);
    }
}
