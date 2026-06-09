<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Persistence;

use App\Users\Domain\Entity\User;
use App\Users\Domain\Port\UserRepositoryPort;

class EloquentUserRepositoryAdapter implements UserRepositoryPort
{
    public function create(User $user): User
    {
        $model = EloquentUserModel::create([
            'id' => $user->id,
            'user_name' => $user->userName,
            'user_password' => $user->userPassword,
            'user_email' => $user->userEmail,
        ]);

        return $this->toDomain($model);
    }

    public function findByEmail(string $email): ?User
    {
        $model = EloquentUserModel::query()
            ->where('user_email', $email)
            ->first();

        return $model instanceof EloquentUserModel ? $this->toDomain($model) : null;
    }

    public function findById(string $id): ?User
    {
        $model = EloquentUserModel::query()->find($id);

        return $model instanceof EloquentUserModel ? $this->toDomain($model) : null;
    }

    public function emailExists(string $email): bool
    {
        return EloquentUserModel::query()
            ->where('user_email', $email)
            ->exists();
    }

    private function toDomain(EloquentUserModel $model): User
    {
        return new User(
            id: (string) $model->id,
            userName: (string) $model->user_name,
            userPassword: (string) $model->user_password,
            userEmail: (string) $model->user_email,
        );
    }
}
