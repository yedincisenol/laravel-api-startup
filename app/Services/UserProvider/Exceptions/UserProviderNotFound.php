<?php

namespace App\Services\UserProvider\Exceptions;

class UserProviderNotFound extends \Exception
{
    public function __construct($message = "User Provider Not Found", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}