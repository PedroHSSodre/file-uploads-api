<?php   

declare(strict_types=1);

namespace App\Folders\Application\UseCase\ListFolder;

use App\Folders\Domain\Port\FolderRepositoryPort;

class ListFolderHandler
{
    public function __construct(
        private readonly FolderRepositoryPort $folderRepositoryPort,
    ) {
    }


    public function handle(string $userId): array
    {
        return $this->folderRepositoryPort->findAllByUserId($userId);
    }
}