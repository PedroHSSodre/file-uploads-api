<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\SignIn;

use App\Users\Domain\Exception\InvalidCredentialsException;
use App\Users\Domain\Port\JwtTokenPort;
use App\Users\Domain\Port\PasswordHasherPort;
use App\Users\Domain\Port\UserRepositoryPort;

class SignInHandler
{
    public function __construct(
        private readonly UserRepositoryPort $userRepositoryPort,
        private readonly PasswordHasherPort $passwordHasherPort,
        private readonly JwtTokenPort $jwtTokenPort,
    ) {
    }

    public function handle(SignInCommand $command): SignInResult
    {
        $user = $this->userRepositoryPort->findByEmail(strtolower($command->user_email));

        if ($user === null || ! $this->passwordHasherPort->check($command->user_password, $user->userPassword)) {
            throw new InvalidCredentialsException();
        }

        return new SignInResult(
            accessToken: $this->jwtTokenPort->generate($user),
            user: $user,
        );
    }
}
