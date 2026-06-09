<?php

declare(strict_types=1);

namespace App\Users\Domain\Port;

use App\Users\Domain\Entity\User;

interface UserRepositoryPort
{
    public function create(User $user): User;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function emailExists(string $email): bool;
}
