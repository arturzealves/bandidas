<?php

namespace App\Services\GPS;

use App\Models\GoogleMapsPromoterMarker;
use App\Models\GoogleMapsUserCircle;

class LocationService
{
    public function isLocationInsideCircle(GoogleMapsPromoterMarker $location, GoogleMapsUserCircle $circle)
    {
        $lat1 = $circle->latitude;
        $lat2 = $location->latitude;
        $lon1 = $circle->longitude;
        $lon2 = $location->longitude;

        $theta = $lon1 - $lon2;
        $distance = 111189.57696 * rad2deg(
            acos(
                sin(deg2rad($lat1)) 
                * sin(deg2rad($lat2)) 
                + cos(deg2rad($lat1)) 
                * cos(deg2rad($lat2)) 
                * cos(deg2rad($theta))
            )
        );

        return $distance < $circle->radius;
    }
}
