<?php

namespace Tests\Feature\Middleware;;

use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnsureUserIsPromoterTest extends TestCase
{
    public function testNormalUserIsRedirectedToDashboard()
    {
        $this->actingAs(User::factory()->make())
            ->get('/map')
            ->assertRedirect(route('dashboard'))
            ->assertStatus(302);
    }

    public function testPromoterIsAbleToAccessRequestedPage()
    {
        $this->actingAs(Promoter::factory()->make())
            ->get('/map')
            ->assertOk();
    }
}
