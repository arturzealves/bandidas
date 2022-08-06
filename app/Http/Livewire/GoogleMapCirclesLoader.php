<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GoogleMapCirclesLoader extends Component
{
    public function render()
    {
        $locations = [
            'lisboa' => [
                'center' => [
                    'lat' => 38.722,
                    'lng' => -9.139,
                ],
                'radius' => 15,
            ],
            'porto' => [
                'center' => [
                    'lat' => 41.157,
                    'lng' => -8.619,
                ],
                'radius' => 10,
            ],
        ];

        return view('livewire.google-map-circles-loader')
            ->with(['locations' => $locations]);
    }
}
