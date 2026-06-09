<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\SignUp;

class SignUpCommand
{
    public function __construct(
        public readonly string $user_name,
        public readonly string $user_email,
        public readonly string $user_password,
    ) {
    }
}
