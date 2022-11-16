<?php

namespace Tests\Unit\Repositories;

use App\Models\Event;
use App\Models\User;
use App\Repositories\EventRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new EventRepository();
    }

    public function testGetUpcomingEvents()
    {
        Event::factory()
            ->count(15)
            ->hasSessions(2)
            ->hasPrices(2)
            ->hasArtists(2)
            ->hasPromoters(1, ['user_type' => User::TYPE_PROMOTER])
            ->create();

        $this->assertEquals(12, $this->repository->getUpcomingEvents()->count());
        $this->assertEquals(3, $this->repository->getUpcomingEvents(3)->count());
    }
}
