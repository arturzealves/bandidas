<?php

namespace App\Listeners;

use App\Events\MapCircleCreated;
use App\Models\MapMarker;
use App\Services\GPS\LocationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MapCircleCreatedListener
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
    public function handle(MapCircleCreated $event)
    {
        $markerLocations = MapMarker::all();

        foreach ($markerLocations as $marker) {
            $distance = $this->locationService->getDistanceBetweenLocationAndCircle($marker, $event->circle);

            if ($distance < $event->circle->radius) {
                $event->circle->promoterMarkers()->attach($marker, ['distance' => $distance]);
            }
        }
    }
}
