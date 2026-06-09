<?php

declare(strict_types=1);

namespace App\Folders\Application\UseCase\CreateFolder;

use App\Folders\Domain\Entity\Folder;
use App\Folders\Domain\Exception\FolderAlreadyExistsException;
use App\Folders\Domain\Exception\FolderParentNotFoundException;
use App\Folders\Domain\Port\FolderRepositoryPort;
use App\Shared\Domain\Port\UuidGeneratorPort;

class CreateFolderHandler
{
    public function __construct(
        private readonly FolderRepositoryPort $folderRepositoryPort,
        private readonly UuidGeneratorPort $uuidGeneratorPort,
    ) {
    }

    public function handle(CreateFolderCommand $command): Folder
    {
        if (
            $command->parentId !== null
            && $this->folderRepositoryPort->findByIdForUser($command->parentId, $command->userId) === null
        ) {
            throw new FolderParentNotFoundException();
        }

        if ($this->folderRepositoryPort->findByUserParentAndName($command->userId, $command->parentId, $command->name) !== null) {
            throw new FolderAlreadyExistsException();
        }

        $folder = new Folder(
            id: $this->uuidGeneratorPort->generate(),
            folderName: $command->name,
            folderParent: $command->parentId,
            folderUserId: $command->userId,
        );

        return $this->folderRepositoryPort->create($folder);
    }
}