<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserRepository();
    }

    public function testGetCountByUserTypeId()
    {
        $userType = UserType::factory()->create();
        $user = User::factory()->create([
            'user_type_id' => $userType
        ]);

        $this->assertEquals(1, $this->repository->getCountByUserTypeId($user->user_type_id));
    }
}
