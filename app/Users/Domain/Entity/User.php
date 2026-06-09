<?php

declare(strict_types=1);

namespace App\Users\Domain\Entity;

class User
{
    public function __construct(
        public readonly string $id,
        public readonly string $userName,
        public readonly string $userPassword,
        public readonly string $userEmail,
    ) {
    }

    /**
     * @return array{
     *   id: string,
     *   user_name: string,
     *   user_email: string
     * }
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->userName,
            'user_email' => $this->userEmail,
        ];
    }
}
