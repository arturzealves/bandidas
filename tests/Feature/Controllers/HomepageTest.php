<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use ProphecyTrait;
    use RefreshDatabase;

    public function testHomepageIsAvailable()
    {
        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertViewIs('homepage')
            ->assertSee('Log in')
            ->assertSee('Register')
            ->assertSee('Upcoming events')
            ->assertSee('Featured Artists');
    }
}
