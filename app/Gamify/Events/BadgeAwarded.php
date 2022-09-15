<?php

namespace App\Gamify\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use QCod\Gamify\Badge;

class BadgeAwarded implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model
     */
    public $user;

    /**
     * @var Badge
     */
    public $badge;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $badgeId integer
     * @param $increment
     */
    public function __construct(Model $user, int $badgeId)
    {
        $this->user = $user;
        $this->badge = $user->badges->find($badgeId);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        $channelName = config('gamify.channel_name') . $this->user->getKey();

        if (config('gamify.broadcast_on_private_channel')) {
            return new PrivateChannel($channelName);
        }

        return new Channel($channelName);
    }
}
