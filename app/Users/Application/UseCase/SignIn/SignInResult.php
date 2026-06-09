<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\SignIn;

use App\Users\Domain\Entity\User;

class SignInResult
{
    public function __construct(
        public readonly string $accessToken,
        public readonly User $user,
    ) {
    }
}
