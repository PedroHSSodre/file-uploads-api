<?php

declare(strict_types=1);

namespace App\Folders\Domain\Entity;

class Folder
{
    public function __construct(
        public readonly string $id,
        public readonly string $folderName,
        public readonly ?string $folderParent,
        public readonly string $folderUserId,
    ) {
    }
}
