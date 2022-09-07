<?php

namespace App\Gamify\Badges;

use QCod\Gamify\BadgeType;

class SpotifyArtistsImported extends BadgeType
{
    /**
     * Description for badge
     *
     * @var string
     */
    protected $description = 'Imported Spotify followed artists';

    /**
     * Check is user qualifies for badge
     *
     * @param $user
     * @return bool
     */
    public function qualifier($user)
    {
        return !empty($user->followedArtists()->get());
    }
}
