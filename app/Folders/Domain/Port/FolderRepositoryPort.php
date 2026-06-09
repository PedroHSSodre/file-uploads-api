<?php

declare(strict_types=1);

namespace App\Folders\Domain\Port;

use App\Folders\Domain\Entity\Folder;

interface FolderRepositoryPort
{
    public function create(Folder $folder): Folder;

    public function findByIdForUser(string $id, string $userId): ?Folder;

    public function findByUserParentAndName(string $userId, ?string $parentId, string $name): ?Folder;
    public function findAllByUserId(string $userId): array;

    public function deleteById(string $id, string $userId): void;
}
