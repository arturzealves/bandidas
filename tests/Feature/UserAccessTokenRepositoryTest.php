<?php

namespace Tests\Feature;

use App\Models\UserAccessToken;
use App\Repositories\UserAccessTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAccessTokenRepositoryTest extends TestCase
{
    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserAccessTokenRepository();
    }

    public function testGetOneByUserIdAndTokenableId()
    {
        $userAccessToken = UserAccessToken::factory()->create();
        $wrongUserAccessToken = UserAccessToken::factory()->create();

        $result = $this->repository->getOneByUserIdAndTokenableId(
            $userAccessToken->user->id, 
            $userAccessToken->tokenable_id
        );

        $this->assertTrue($userAccessToken->is($result));
        $this->assertEquals($userAccessToken->getAttributes(), $result->getAttributes());
        $this->assertFalse($wrongUserAccessToken->is($result));
        $this->assertNotEquals($wrongUserAccessToken->getAttributes(), $result->getAttributes());
    }
}
