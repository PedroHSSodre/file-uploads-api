<?php

declare(strict_types=1);

namespace App\UserAccess\Infrastructure\Security;

use App\UserAccess\Domain\Port\PasswordHasherPort;
use Illuminate\Support\Facades\Hash;

class LaravelPasswordHasherAdapter implements PasswordHasherPort
{
    public function make(string $plainPassword): string
    {
        return Hash::make($plainPassword);
    }

    public function check(string $plainPassword, string $hashedPassword): bool
    {
        return Hash::check($plainPassword, $hashedPassword);
    }
}
