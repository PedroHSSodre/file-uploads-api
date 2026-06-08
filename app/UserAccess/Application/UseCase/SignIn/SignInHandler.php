<?php

declare(strict_types=1);

namespace App\UserAccess\Application\UseCase\SignIn;

use App\UserAccess\Domain\Exception\InvalidCredentialsException;
use App\UserAccess\Domain\Port\JwtTokenPort;
use App\UserAccess\Domain\Port\PasswordHasherPort;
use App\UserAccess\Domain\Port\UserRepositoryPort;

class SignInHandler
{
    public function __construct(
        private readonly UserRepositoryPort $userRepositoryPort,
        private readonly PasswordHasherPort $passwordHasherPort,
        private readonly JwtTokenPort $jwtTokenPort,
    ) {
    }

    /**
     * @param array{
     *   user_email: string,
     *   user_password: string
     * } $payload
     *
     * @return array{
     *   access_token: string,
     *   user: array{
     *     id: string,
     *     user_name: string,
     *     user_email: string
     *   }
     * }
     */
    public function handle(array $payload): array
    {
        $user = $this->userRepositoryPort->findByEmail(strtolower($payload['user_email']));

        if ($user === null || ! $this->passwordHasherPort->check($payload['user_password'], $user->userPassword)) {
            throw new InvalidCredentialsException();
        }

        return [
            'access_token' => $this->jwtTokenPort->generate($user),
            'user' => $user->toPublicArray(),
        ];
    }
}
