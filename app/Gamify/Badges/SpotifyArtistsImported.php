<?php

namespace App\Gamify\Badges;

use App\Gamify\BadgeType;

/**
 * known issue: it is not assigning this badge correctly because
 * we don't track when the user clicked on the import artists button
 */
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
        return $this->userHasNotReceivedThisBadgeYet($user)
            && $user->followedArtists()->get()->isNotEmpty();
    }
}
