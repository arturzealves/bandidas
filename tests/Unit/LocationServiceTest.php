<?php

namespace Tests\Unit;

use App\Models\GoogleMapsPromoterMarker;
use App\Models\GoogleMapsUserCircle;
use App\Services\GPS\LocationService;
use PHPUnit\Framework\TestCase;

class LocationServiceTest extends TestCase
{
    public function testIsLocationInsideCircle()
    {
        $circle = new GoogleMapsUserCircle();
        $circle->latitude = 38.704429;
        $circle->longitude = -9.145777;
        $circle->radius = 34;

        $markerInsideCircle = new GoogleMapsPromoterMarker();
        $markerInsideCircle->latitude = 38.704390;
        $markerInsideCircle->longitude = -9.145781;

        $markerOutsideCircle = new GoogleMapsPromoterMarker();
        $markerOutsideCircle->latitude = 38.704737;
        $markerOutsideCircle->longitude = -9.145832;

        $locationService = new LocationService();
        $this->assertTrue(
            $locationService->isLocationInsideCircle($markerInsideCircle, $circle)
        );
        $this->assertFalse(
            $locationService->isLocationInsideCircle($markerOutsideCircle, $circle)
        );
    }
}
