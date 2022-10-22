<?php

namespace Tests\Unit\Gamify\Events;

use App\Gamify\Events\BadgeAwarded;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BadgeAwardedTest extends TestCase
{
    private $event;
    private $user;
    private $badgeId;

    public function setUp(): void
    {
        parent::setUp();

        $this->badgeId = 123;
        $this->user = User::factory()->create();
        // dd($this->user);
        $this->event = new BadgeAwarded($this->user, $this->badgeId);
    }
    
    public function testbroadcastOnPrivateChannel()
    {
        Config::set('gamify.broadcast_on_private_channel', true);
        
        $this->assertInstanceOf(
            PrivateChannel::class,
            $this->event->broadcastOn()
        );
    }

    public function testbroadcastOnPublicChannel()
    {
        Config::set('gamify.broadcast_on_private_channel', false);
        
        $this->assertInstanceOf(
            Channel::class,
            $this->event->broadcastOn()
        );
    }
}
