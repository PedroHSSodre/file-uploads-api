<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Folders\Domain\Entity\Folder;

class FolderResource
{
    /**
     * @return array{
     *   id: string,
     *   folder_name: string,
     *   folder_parent: string|null,
     *   folder_user_id: string,
     * }
     */
    public static function toArray(Folder $folder): array
    {
        return [
            'id' => $folder->id,
            'folder_name' => $folder->folderName,
            'folder_parent' => $folder->folderParent,
        ];
    }
}
