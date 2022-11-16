<?php

namespace Tests\Feature\Controllers;

use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function testUserDashboardIsAvailableToUsers()
    {
        $this->actingAs(User::factory()->make())
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard.user');
    }

    public function testPromoterDashboardIsAvailableToPromoters()
    {
        $this->actingAs(Promoter::factory()->make())
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard.promoter');
    }
}
