<?php

namespace Tests\Feature;

use App\Events\MapMarkerCreated;
use App\Listeners\MapMarkerCreatedListener;
use App\Models\MapCircle;
use App\Models\MapMarker;
use App\Services\GPS\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class MapMarkerCreatedListenerTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;
    
    private $listener;
    private $locationService;

    public function setUp(): void
    {
        parent::setUp();

        $this->locationService = $this->prophesize(LocationService::class);
        $this->listener = new MapMarkerCreatedListener($this->locationService->reveal());
    }
    
    public function testHandle()
    {
        $mapMarker = MapMarker::factory()->create();
        
        $mapCircle = MapCircle::factory()->create([
            'latitude' => $mapMarker->latitude + 0.0001,
            'longitude' => $mapMarker->longitude + 0.0001,

        ]);
        $event = new MapMarkerCreated($mapMarker);

        $this->locationService
            ->getDistanceBetweenLocationAndCircle($event->marker, Argument::type(MapCircle::class))
            ->shouldBeCalledTimes(1)
            ->willReturn(10);
        
        $this->listener->handle($event);
        
        $this->assertContains($mapCircle->id, $event->marker->userCircles->pluck('id'));
    }
}
