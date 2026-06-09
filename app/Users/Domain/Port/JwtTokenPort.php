<?php

declare(strict_types=1);

namespace App\Users\Domain\Port;

use App\Users\Domain\Entity\User;

interface JwtTokenPort
{
    public function generate(User $user): string;

    /**
     * @return array{
     *   sub: string,
     *   user_email?: string,
     *   user_name?: string,
     *   iat?: int,
     *   exp?: int
     * }|null
     */
    public function validate(string $token): ?array;
}
