<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAccessToken;
use App\Repositories\UserAccessTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAccessTokenRepositoryTest extends TestCase
{
    use RefreshDatabase;
    
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

    public function testFirstOrCreate()
    {
        $this->assertDatabaseCount('user_access_tokens', 0);
        
        $user = User::factory()->create();
        
        $this->repository->firstOrCreate(
            $user->id,
            UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN,
            UserAccessToken::TOKENABLE_TYPE_SPOTIFY_ACCESS_TOKEN,
            UserAccessToken::NAME_SPOTIFY_ACCESS_TOKEN,
            'test access token',
            'test refresh token',
            ['testScope1', 'testScope2'],
            12321
        );

        $this->assertDatabaseCount('user_access_tokens', 1);
    }
}
