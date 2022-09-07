<?php

namespace App\Gamify\Points;

use App\Models\User;
use QCod\Gamify\PointType;

class SpotifyArtistsImported extends PointType
{
    /**
     * Number of points
     *
     * @var int
     */
    public $points = 5;

    public $allowDuplicates = false;

    /**
     * Point constructor
     *
     * @param $subject
     */
    public function __construct(User $subject)
    {
        $this->subject = $subject;
    }

    /**
     * User who will be receive points
     *
     * @return mixed
     */
    public function payee()
    {
        return $this->getSubject();
    }
}
