<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Users\Domain\Entity\User;

class SignUpResource
{
    /**
     * @return array{
     *   id: string,
     *   user_name: string,
     *   user_email: string
     * }
     */
    public static function toArray(User $user): array
    {
        return [
            'id' => $user->id,
            'user_name' => $user->userName,
            'user_email' => $user->userEmail,
        ];
    }
}
