<?php

namespace App\Exceptions;

use Exception;

class UserAccessTokenNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('The logged user does not have any UserAccessToken');
    }
}
