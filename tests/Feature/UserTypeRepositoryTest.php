<?php

namespace Tests\Feature;

use App\Models\UserType;
use App\Repositories\UserTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserTypeRepository();
    }

    public function testGetAll()
    {
        UserType::factory()->count(3)->create();

        $result = $this->repository->getAll();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(3, $result->count());
    }

    public function testGetByName()
    {
        UserType::factory()->create(['name' => UserType::TYPE_USER]);

        $result = $this->repository->getByName(UserType::TYPE_USER);
        $this->assertInstanceOf(UserType::class, $result);
        $this->assertEquals(UserType::TYPE_USER, $result->name);
    }
}
