<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class FirstMapCircle extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'First Map Circle';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user)
    {
        return $user->mapCircles()->count() >= 1;
    }
}
