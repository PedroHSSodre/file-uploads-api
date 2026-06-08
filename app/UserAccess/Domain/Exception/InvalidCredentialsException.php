<?php

declare(strict_types=1);

namespace App\UserAccess\Domain\Exception;

use RuntimeException;

class InvalidCredentialsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials.');
    }
}
