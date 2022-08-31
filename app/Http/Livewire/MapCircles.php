<?php

namespace App\Http\Livewire;

use App\Events\MapCircleCreated;
use App\Models\MapMarker;
use Livewire\Component;
use App\Models\MapCircle;
use App\Models\ArtistMapCircle;
use App\Services\GPS\LocationService;
use Illuminate\Support\Facades\Auth;

class MapCircles extends Component
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

        $circle = MapCircle::find($id);
        $this->name = $circle->name;
        $this->circle_id = $circle->id;
        $this->latitude = $circle->latitude;
        $this->longitude = $circle->longitude;
        $this->radius = $circle->radius;
        $this->isCircleSelected = true;

        $this->selectedCircleBudget = optional($circle->artists()->first())->budget;
    }

    public function destroy($id)
    {
        $object = MapCircle::find($id);
        if (!empty($object)) {
            $object->delete();
        }
    }

    public function render(LocationService $locationService)
    {
        $user = Auth::user();
        $circleLocations = MapCircle::where('user_id', $user->id)->get();
        $markerLocations = MapMarker::all();

        $locationsInsideCircles = collect();
        foreach ($markerLocations as $index => $marker) {
            foreach ($circleLocations as $circle) {
                if ($locationService->isLocationInsideCircle($marker, $circle)) {
                    $locationsInsideCircles->push($marker);
                    $markerLocations->forget($index);
                }
            }
        }

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
            'user_id' => Auth::user()->id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
        ]);

        MapCircleCreated::dispatch($circle);

        $this->circle_id = $circle->id;
    }

    public function update()
    {
        MapCircle::updateOrCreate(
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
