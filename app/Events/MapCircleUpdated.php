<?php

namespace App\Events;

use App\Models\MapCircle;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MapCircleUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $circle;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MapCircle $circle)
    {
        $this->circle = $circle;
    }
}
