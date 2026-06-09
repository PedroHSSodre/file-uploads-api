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
     *   children: array<int, mixed>,
     * }
     */
    public static function toArray(Folder $folder, array $children = []): array
    {
        return [
            'id' => $folder->id,
            'folder_name' => $folder->folderName,
            'folder_parent' => $folder->folderParent,
            'children' => $children,
        ];
    }

    /**
     * @param list<Folder> $folders
     *
     * @return list<array{
     *   id: string,
     *   folder_name: string,
     *   folder_parent: string|null,
     *   children: array<int, mixed>,
     * }>
     */
    public static function collection(array $folders): array
    {
        $childrenByParent = [];

        foreach ($folders as $folder) {
            $childrenByParent[$folder->folderParent ?? 'root'][] = $folder;
        }

        return self::buildTree($childrenByParent);
    }

    /**
     * @param array<string, list<Folder>> $childrenByParent
     *
     * @return list<array{
     *   id: string,
     *   folder_name: string,
     *   folder_parent: string|null,
     *   children: array<int, mixed>,
     * }>
     */
    private static function buildTree(array $childrenByParent, ?string $parentId = null): array
    {
        $folders = $childrenByParent[$parentId ?? 'root'] ?? [];

        return array_map(
            fn (Folder $folder) => self::toArray(
                $folder,
                self::buildTree($childrenByParent, $folder->id),
            ),
            $folders,
        );
    }
}
