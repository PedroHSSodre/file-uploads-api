<?php

declare(strict_types=1);

namespace App\Users\Domain\Port;

interface PasswordHasherPort
{
    public function make(string $plainPassword): string;

    public function check(string $plainPassword, string $hashedPassword): bool;
}
