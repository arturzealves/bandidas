<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct($message = 'The requested User was not found')
    {
        parent::__construct($message);
    }
}
