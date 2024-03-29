<?php

namespace App\Gamify\Points;

use App\Models\MapCircle;
use QCod\Gamify\PointType;

class MapCircleCreated extends PointType
{
    /**
     * Number of points
     *
     * @var int
     */
    public $points = 1;

    /**
     * Point constructor
     *
     * @param $subject
     */
    public function __construct(MapCircle $subject)
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
        return $this->getSubject()->user;
    }
}
