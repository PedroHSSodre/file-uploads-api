<?php

declare(strict_types=1);

namespace App\Users\Domain\Exception;

use RuntimeException;

class UserAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User email already exists.');
    }
}
