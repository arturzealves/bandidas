<?php

namespace Tests\Feature\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;

    public function testEventPageIsAvailable()
    {
        $event = Event::factory()
            ->hasSessions(2)
            ->hasPrices(2)
            ->hasArtists(2)
            ->hasPromoters(1, ['user_type' => User::TYPE_PROMOTER])
            ->create();

        $response = $this->get('/events/' . $event->uuid);
        $response->assertStatus(200)
            ->assertViewIs('events.show')
            ->assertSee($event->name);
    }
}
