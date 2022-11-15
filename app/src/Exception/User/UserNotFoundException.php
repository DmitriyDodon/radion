<?php

namespace App\Exception\User;

use Throwable;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = "There is no such user.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}