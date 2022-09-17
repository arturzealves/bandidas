<?php

namespace App\Gamify;

use App\Gamify\Events\BadgeAwarded;

trait HasBadges
{
    /**
     * Badges user relation
     *
     * @return mixed
     */
    public function badges()
    {
        return $this->belongsToMany(config('gamify.badge_model'), 'user_badges')
            ->withTimestamps();
    }

    /**
     * Sync badges for qiven user
     *
     * @param $user
     */
    public function syncBadges($user = null)
    {
        $user = is_null($user) ? $this : $user;

        $badgeIds = app('badges')->filter
            ->qualifier($user)
            ->map->getBadgeId();

        $user->badges()->syncWithoutDetaching($badgeIds);

        foreach ($badgeIds as $badgeId) {
            BadgeAwarded::dispatch($user, $badgeId);
        }
    }
}
