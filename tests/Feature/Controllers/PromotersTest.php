<?php

namespace Tests\Feature\Controllers;

use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class PromotersTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;

    public function testPromotersPageIsAvailable()
    {
        $promoter = Promoter::factory()->create();

        $this->get('/promoters/' . $promoter->id)
            ->assertStatus(200)
            ->assertViewIs('promoters.show')
            ->assertSee($promoter->name);
    }

    public function testPromotersPageReturns404IfPromoterDoesNotExist()
    {
        $promoter = Promoter::factory()->make();

        $this->get('/promoters/' . $promoter->id)
            ->assertStatus(404);
    }
}
