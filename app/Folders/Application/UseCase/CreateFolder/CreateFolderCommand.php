<?php

declare(strict_types=1);

namespace App\Folders\Application\UseCase\CreateFolder;

use App\Folders\Domain\Exception\InvalidFolderNameException;

class CreateFolderCommand
{
    public readonly string $name;

    public function __construct(
        string $name,
        public readonly ?string $parentId,
        public readonly string $userId,
    ) {
        $this->name = trim($name);

        if ($this->name === '') {
            throw new InvalidFolderNameException();
        }
    }
}
