<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\GoogleMapsUserCircle;
use Illuminate\Support\Facades\Auth;

class GoogleMapsUserCircles extends Component
{
    public $name;
    public $circle_id;
    public $latitude;
    public $longitude;
    public $radius;

    protected $rules = [
        'name' => 'optional|string',
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

        $locations = GoogleMapsUserCircle::where('user_id', $user->id)->get();

        return view('livewire.google-maps-user-circles')
            ->with(['locations' => $locations]);
    }

    public function submit()
    {
        $this->validate();

        GoogleMapsUserCircle::create([
            'name' => $this->name,
            'user_id' => Auth::user()->id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
        ]);

        $this->reset();
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
