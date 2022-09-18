<?php

namespace Tests\Unit\Listeners;

use App\Events\MapCircleCreated;
use App\Listeners\MapCircleCreatedListener;
use App\Models\MapCircle;
use App\Models\MapMarker;
use App\Services\GPS\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class MapCircleCreatedListenerTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;
    
    private $listener;
    private $locationService;

    public function setUp(): void
    {
        parent::setUp();

        $this->locationService = $this->prophesize(LocationService::class);
        $this->listener = new MapCircleCreatedListener($this->locationService->reveal());
    }

    public function testHandle()
    {
        $mapCircle = MapCircle::factory()->create();
        $event = new MapCircleCreated($mapCircle);
        
        MapMarker::factory()->create([
            'latitude' => $mapCircle->latitude + 0.0001,
            'longitude' => $mapCircle->longitude + 0.0001,
        ]);

        $this->locationService
            ->getDistanceBetweenLocationAndCircle(Argument::type(MapMarker::class), $event->circle)
            ->shouldBeCalledTimes(1)
            ->willReturn(10);
        
        $this->assertEquals(0, $mapCircle->user->reputation);
        
        $this->listener->handle($event);
        
        $this->assertEquals(1, $mapCircle->user->reputation);
    }
}
