<?php

declare(strict_types=1);

namespace App\UserAccess\Domain\Port;

use App\UserAccess\Domain\Entity\User;

interface UserRepositoryPort
{
    public function create(User $user): User;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function emailExists(string $email): bool;
}
