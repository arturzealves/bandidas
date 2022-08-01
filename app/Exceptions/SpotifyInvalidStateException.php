<?php

namespace App\Exceptions;

use Exception;

class SpotifyInvalidStateException extends Exception
{
    public function __construct()
    {
        parent::__construct('The Spotify authentication state does not match');
    }
}
