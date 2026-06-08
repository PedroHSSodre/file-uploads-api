<?php

declare(strict_types=1);

namespace App\UserAccess\Application\UseCase\SignUp;

use App\UserAccess\Domain\Entity\User;
use App\UserAccess\Domain\Exception\UserAlreadyExistsException;
use App\UserAccess\Domain\Port\PasswordHasherPort;
use App\UserAccess\Domain\Port\UserRepositoryPort;
use Illuminate\Support\Str;

class SignUpHandler
{
    public function __construct(
        private readonly UserRepositoryPort $userRepositoryPort,
        private readonly PasswordHasherPort $passwordHasherPort,
    ) {
    }

    /**
     * @param array{
     *   user_name: string,
     *   user_password: string,
     *   user_email: string
     * } $payload
     *
     * @return array{
     *   id: string,
     *   user_name: string,
     *   user_email: string
     * }
     */
    public function handle(array $payload): array
    {
        $email = strtolower($payload['user_email']);

        if ($this->userRepositoryPort->emailExists($email)) {
            throw new UserAlreadyExistsException();
        }

        $user = new User(
            id: (string) Str::uuid(),
            userName: $payload['user_name'],
            userPassword: $this->passwordHasherPort->make($payload['user_password']),
            userEmail: $email,
        );

        return $this->userRepositoryPort->create($user)->toPublicArray();
    }
}
