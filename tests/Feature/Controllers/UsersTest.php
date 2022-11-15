<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;

    public function testUsersPageIsAvailable()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/users/' . $user->username)
            ->assertStatus(200)
            ->assertViewIs('profile.show-public-profile')
            ->assertSee($user->name);
    }

    public function testUsersPageReturns404IfUserDoesNotExist()
    {
        $user = User::factory()->make();

        $this->actingAs($user)
            ->get('/users/' . $user->username)
            ->assertStatus(404);
    }
}
