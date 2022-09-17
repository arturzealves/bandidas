<?php

namespace App\Gamify;

use App\Models\User;
use QCod\Gamify\BadgeType as GamifyBadgeType;

abstract class BadgeType extends GamifyBadgeType
{
    public function userHasNotReceivedThisBadgeYet(User $user)
    {
        return $user->badges()
            ->where('name', $this->getName())
            ->get()
            ->isEmpty();
    }
}
