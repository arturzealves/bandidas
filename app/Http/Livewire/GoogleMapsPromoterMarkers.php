<?php

namespace App\Http\Livewire;

use App\Models\GoogleMapsPromoterMarker;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GoogleMapsPromoterMarkers extends Component
{
    public $latitude;
    public $longitude;

    protected $rules = [
        'latitude' => 'required|numeric|min:-90|max:90',
        'longitude' => 'required|numeric|min:-180|max:180',
    ];

    public function render()
    {
        $user = Auth::user();
        $locations = GoogleMapsPromoterMarker::where('user_id', $user->id)->get();

        return view('livewire.google-maps-promoter-markers')
            ->with([
                'user' => $user,
                // 'userLocation' => $user->location,
                'locations' => $locations,
            ])
        ;
    }

    public function submit()
    {
        $this->validate();

        $marker = GoogleMapsPromoterMarker::create([
            'user_id' => Auth::user()->id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $this->marker_id = $marker->id;
    }
}
