<?php

namespace App\Http\Livewire;

use App\Events\MapCircleCreated;
use App\Events\MapCircleUpdated;
use App\Models\MapCircle;
use App\Models\MapMarker;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MapCircles extends Component
{
    public $name = 'default name';
    public $circle_uuid;
    public $latitude;
    public $longitude;
    public $radius;
    public $budget = 0;
    // public $isCircleSelected = true;
    public $selectedCircleBudget = 0;

    protected $rules = [
        // 'name' => 'optional|string',
        'latitude' => 'required|numeric|min:-90|max:90',
        'longitude' => 'required|numeric|min:-180|max:180',
        'radius' => 'required|numeric|max:8388607',
        'budget' => 'numeric|min:0|max:99999',
    ];

    protected $listeners = [
        'destroy' => 'destroy',
        'mount' => 'mount',
    ];

    public function mount($uuid = null) {
        if (null === $uuid) {
            return;
        }

        $circle = MapCircle::find($uuid);
        $this->name = $circle->name;
        $this->circle_uuid = $circle->uuid;
        $this->latitude = $circle->latitude;
        $this->longitude = $circle->longitude;
        $this->radius = $circle->radius;
        $this->budget = $circle->budget;
        // $this->isCircleSelected = true;

        $this->selectedCircleBudget = optional($circle->artists()->first())->budget;
        $this->dispatchBrowserEvent('mounted', ['uuid' => $this->circle_uuid]);
    }

    public function destroy($uuid)
    {
        $object = MapCircle::find($uuid);
        if (!empty($object)) {
            $object->delete();
        }
    }

    public function render()
    {
        $user = Auth::user();
        $circleLocations = MapCircle::where('user_uuid', $user->uuid)->get();

        $locationsInsideCircles = collect();
        foreach ($circleLocations as $circle) {
            foreach ($circle->promoterMarkers()->get() as $marker) {
                if (!$locationsInsideCircles->contains($marker)) {
                    $locationsInsideCircles->add($marker);
                }
            }
        }

        $markerLocations = MapMarker::all()->diff($locationsInsideCircles);

        return view('livewire.map-circles')
            ->with([
                'user' => $user,
                'userLocation' => $user->location,
                'circleLocations' => $circleLocations,
                'markerLocations' => $markerLocations,
                'locationsInsideCircles' => $locationsInsideCircles,
            ]);
    }

    public function submit()
    {
        $this->validate();

        $circle = MapCircle::create([
            'name' => $this->name,
            'user_uuid' => Auth::user()->uuid,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
        ]);

        $this->circle_uuid = $circle->uuid;

        MapCircleCreated::dispatch($circle);
        $this->dispatchBrowserEvent('submitted', ['uuid' => $this->circle_uuid]);
    }

    public function update()
    {
        $circle = MapCircle::updateOrCreate(
            [
                'uuid' => $this->circle_uuid,
            ],
            [
                'name' => $this->name,
                'user_uuid' => Auth::user()->uuid,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius' => $this->radius,
                'budget' => $this->budget,
            ],
        );

        MapCircleUpdated::dispatch($circle);
        
        $this->reset();
    }
}
