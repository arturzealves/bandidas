<?php

namespace Tests\Unit\Listeners;

use App\Events\MapCircleUpdated;
use App\Listeners\MapCircleUpdatedListener;
use App\Models\MapCircle;
use App\Models\MapMarker;
use App\Services\GPS\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class MapCircleUpdatedListenerTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;
    
    private $listener;
    private $locationService;

    public function setUp(): void
    {
        parent::setUp();

        $this->locationService = $this->prophesize(LocationService::class);
        $this->listener = new MapCircleUpdatedListener($this->locationService->reveal());
    }
    
    public function testHandle()
    {
        $mapCircle = MapCircle::factory()->create();
        $event = new MapCircleUpdated($mapCircle);
        
        $mapMarker = MapMarker::factory()->create([
            'latitude' => $mapCircle->latitude + 0.0001,
            'longitude' => $mapCircle->longitude + 0.0001,
        ]);

        $this->locationService
            ->getDistanceBetweenLocationAndCircle(Argument::type(MapMarker::class), $event->circle)
            ->shouldBeCalledTimes(1)
            ->willReturn(10);
        
        $this->listener->handle($event);
        
        $this->assertContains($mapMarker->id, $event->circle->promoterMarkers->pluck('id'));
    }
}
