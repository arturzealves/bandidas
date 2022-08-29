<?php

namespace App\Http\Livewire;

use App\Models\GoogleMapsPromoterMarker;
use Livewire\Component;
use App\Models\GoogleMapsUserCircle;
use App\Models\GoogleMapsUserCirclesHasArtist;
use Illuminate\Support\Facades\Auth;

class GoogleMapsUserCircles extends Component
{
    public $name = 'default name';
    public $circle_id;
    public $latitude;
    public $longitude;
    public $radius;
    public $isCircleSelected = true;
    public $selectedCircleBudget = 0;

    protected $rules = [
        // 'name' => 'optional|string',
        'latitude' => 'required|numeric|min:-90|max:90',
        'longitude' => 'required|numeric|min:-180|max:180',
        'radius' => 'required|numeric|max:16777215',
    ];

    protected $listeners = [
        'destroy' => 'destroy',
        'mount' => 'mount',
    ];

    public function mount($id = null) {
        if (null === $id) {
            return;
        }

        $object = GoogleMapsUserCircle::find($id);
        $this->name = $object->name;
        $this->circle_id = $object->id;
        $this->latitude = $object->latitude;
        $this->longitude = $object->longitude;
        $this->radius = $object->radius;
        $this->isCircleSelected = true;

        $this->selectedCircleBudget = optional(GoogleMapsUserCirclesHasArtist::where('google_maps_user_circle_id', $this->circle_id)
            ->first())->budget;
    }

    public function destroy($id)
    {
        $object = GoogleMapsUserCircle::find($id);
        if (!empty($object)) {
            $object->delete();
        }
    }

    public function render()
    {
        $user = Auth::user();
        $circleLocations = GoogleMapsUserCircle::where('user_id', $user->id)->get();
        $markerLocations = GoogleMapsPromoterMarker::all();

        return view('livewire.google-maps-user-circles')
            ->with([
                'user' => $user,
                'userLocation' => $user->location,
                'circleLocations' => $circleLocations,
                'markerLocations' => $markerLocations,
            ]);
    }

    public function submit()
    {
        $this->validate();

        $circle = GoogleMapsUserCircle::create([
            'name' => $this->name,
            'user_id' => Auth::user()->id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
        ]);

        $this->circle_id = $circle->id;
    }

    public function update()
    {
        GoogleMapsUserCircle::updateOrCreate(
            [
                'id' => $this->circle_id,
            ],
            [
                'name' => $this->name,
                'user_id' => Auth::user()->id,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius' => $this->radius,
            ],
        );

        $this->reset();
    }
}
