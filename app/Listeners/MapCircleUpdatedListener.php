<?php

namespace App\Listeners;

use App\Events\MapCircleUpdated;
use App\Models\MapMarker;
use App\Services\GPS\LocationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MapCircleUpdatedListener
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
    public function handle(MapCircleUpdated $event)
    {
        $markersToSync = [];

        $markerLocations = MapMarker::all();
        foreach ($markerLocations as $marker) {
            $distance = $this->locationService->getDistanceBetweenLocationAndCircle($marker, $event->circle);

            if ($distance < $event->circle->radius) {
                $markersToSync[$marker->uuid] = ['distance' => $distance];
            }
        }

        $event->circle->promoterMarkers()->sync($markersToSync);
    }
}
