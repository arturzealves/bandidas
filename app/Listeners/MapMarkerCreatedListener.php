<?php

namespace App\Listeners;

use App\Events\MapMarkerCreated;
use App\Models\MapCircle;
use App\Models\MapMarker;
use App\Services\GPS\LocationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MapMarkerCreatedListener
{
    private $locationService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MapMarkerCreated $event)
    {
        $circles = MapCircle::all();

        foreach ($circles as $circle) {
            $distance = $this->locationService->getDistanceBetweenLocationAndCircle($event->marker, $circle);

            if ($distance < $circle->radius) {
                $event->marker->userCircles()->attach($circle);
            }
        }
    }
}
