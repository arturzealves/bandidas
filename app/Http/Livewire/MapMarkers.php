<?php

namespace App\Http\Livewire;

use App\Events\MapMarkerCreated;
use App\Models\MapMarker;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MapMarkers extends Component
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
        $locations = MapMarker::where('user_uuid', $user->uuid)->get();

        return view('livewire.map-markers')
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

        $marker = MapMarker::create([
            'user_uuid' => Auth::user()->uuid,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        MapMarkerCreated::dispatch($marker);

        $this->marker_id = $marker->id;
    }
}
