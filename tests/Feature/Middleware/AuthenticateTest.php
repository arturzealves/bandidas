<?php

namespace Tests\Feature\Middleware;

use App\Providers\RouteServiceProvider;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    public function testAuthenticateRedirectsToLoginPageIfNotExpectingJson()
    {
        $this->get(RouteServiceProvider::HOME)
            ->assertRedirect(route('login'))
            ->assertStatus(302);
    }

    public function testAuthenticateDoesNotRedirectToLoginPageIfItExpectsJson()
    {
        $this->getJson(RouteServiceProvider::HOME)
            ->assertStatus(401);

        $this->getJson('/')
            ->assertStatus(200);
    }
}
