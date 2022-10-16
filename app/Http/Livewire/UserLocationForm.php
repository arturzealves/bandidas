<?php

namespace App\Http\Livewire;

use App\Models\UserLocation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserLocationForm extends Component
{
    public $latitude;
    public $longitude;

    protected $listeners = [
        'submit' => 'submit',
    ];

    protected $rules = [
        'latitude' => 'required|numeric|min:-90|max:90',
        'longitude' => 'required|numeric|min:-180|max:180',
    ];

    public function submit()
    {
        $this->validate();

        UserLocation::updateOrCreate([
            'user_uuid' => Auth::user()->uuid,
        ],
        [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.user-location-form');
    }
}
