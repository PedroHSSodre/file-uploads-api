<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Identifier;

use App\Shared\Domain\Port\UuidGeneratorPort;
use Illuminate\Support\Str;

class LaravelUuidGeneratorAdapter implements UuidGeneratorPort
{
    public function generate(): string
    {
        return (string) Str::uuid();
    }
}
