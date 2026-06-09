<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\SignUp;

use App\Shared\Domain\Port\UuidGeneratorPort;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Exception\UserAlreadyExistsException;
use App\Users\Domain\Port\PasswordHasherPort;
use App\Users\Domain\Port\UserRepositoryPort;

class SignUpHandler
{
    public function __construct(
        private readonly UserRepositoryPort $userRepositoryPort,
        private readonly PasswordHasherPort $passwordHasherPort,
        private readonly UuidGeneratorPort $uuidGeneratorPort,
    ) {
    }

    /**
     * @return array{
     *   id: string,
     *   user_name: string,
     *   user_email: string
     * }
     */
    public function handle(SignUpCommand $command): User
    {
        $email = strtolower($command->user_email);

        if ($this->userRepositoryPort->emailExists($email)) {
            throw new UserAlreadyExistsException();
        }

        $user = new User(
            id: $this->uuidGeneratorPort->generate(),
            userName: $command->user_name,
            userPassword: $this->passwordHasherPort->make($command->user_password),
            userEmail: $email,
        );

        return $this->userRepositoryPort->create($user);
    }
}
