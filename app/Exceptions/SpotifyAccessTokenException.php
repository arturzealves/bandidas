<?php

namespace App\Exceptions;

use Exception;

class SpotifyAccessTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct('The Spotify access token is invalid');
    }
}
