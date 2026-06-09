<?php

declare(strict_types=1);

namespace App\Folders\Application\UseCase\DeleteFolder;

use App\Folders\Domain\Exception\FolderNotFoundException;
use App\Folders\Domain\Port\FolderRepositoryPort;

class DeleteFolderHandler
{
    public function __construct(
        private readonly FolderRepositoryPort $folderRepositoryPort,
    ) {
    }

    public function handle(string $id, string $userId): void
    {
        $folder = $this->folderRepositoryPort->findByIdForUser($id, $userId);

        if ($folder === null) {
            throw new FolderNotFoundException();
        }

        $this->folderRepositoryPort->deleteById($id, $userId);
    }
}