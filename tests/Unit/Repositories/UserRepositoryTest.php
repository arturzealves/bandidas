<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserRepository();
    }

    public function testGetCountByUserType()
    {
        User::factory()->create([
            'user_type' => USER::TYPE_PROMOTER
        ]);

        $this->assertEquals(1, $this->repository->getCountByUserType(USER::TYPE_PROMOTER));
    }
}
