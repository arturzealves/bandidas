<?php

namespace Tests\Feature;

use App\Gamify\Gamify;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class HasLevelSystemTest extends TestCase
{
    use Gamify;
    
    public function testLevelThrowsExceptionIfLevelSystemIsNotConfigured()
    {
        Config::set('gamify.enable_level_system', false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The config gamify.enable_level_system must be enabled');
        $this->level();
    }

    public function testLevelSystemIsEnabled()
    {
        Config::set('gamify.enable_level_system', true);
        
        $this->reputation = 9;
        $this->assertEquals(1, $this->level());

        $this->reputation = 10;
        $this->assertEquals(2, $this->level());

        $this->reputation = 200;
        $this->assertEquals(4, $this->level());
    }
}
