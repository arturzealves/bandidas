<?php

namespace App\Services\GPS;

use App\Models\MapMarker;
use App\Models\MapCircle;

class LocationService
{
    public function isLocationInsideCircle(MapMarker $location, MapCircle $circle)
    {
        $distance = $this->getDistanceBetweenLocationAndCircle($location, $circle);

        return $distance < $circle->radius;
    }

    public function getDistanceBetweenLocationAndCircle(MapMarker $location, MapCircle $circle)
    {
        $lat1 = $circle->latitude;
        $lat2 = $location->latitude;
        $lon1 = $circle->longitude;
        $lon2 = $location->longitude;

        $theta = $lon1 - $lon2;

        return 111189.57696 * rad2deg(
            acos(
                sin(deg2rad($lat1)) 
                * sin(deg2rad($lat2)) 
                + cos(deg2rad($lat1)) 
                * cos(deg2rad($lat2)) 
                * cos(deg2rad($theta))
            )
        );
    }
}
