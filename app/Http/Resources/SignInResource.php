<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Users\Application\UseCase\SignIn\SignInResult;

class SignInResource
{
    /**
     * @return array{
     *   access_token: string,
     *   user: array{
     *     id: string,
     *     user_name: string,
     *     user_email: string
     *   }
     * }
     */
    public static function toArray(SignInResult $result): array
    {
        return [
            'access_token' => $result->accessToken,
            'user' => [
                'id' => $result->user->id,
                'user_name' => $result->user->userName,
                'user_email' => $result->user->userEmail,
            ],
        ];
    }
}
