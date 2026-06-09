<?php

declare(strict_types=1);

namespace App\Folders\Infrastructure\Persistence;

use App\Folders\Domain\Entity\Folder;
use App\Folders\Domain\Port\FolderRepositoryPort;

class EloquentFolderRepository implements FolderRepositoryPort
{
    public function create(Folder $folder): Folder
    {
        $model = EloquentFolderModel::create([
            'id' => $folder->id,
            'folder_name' => $folder->folderName,
            'folder_parent' => $folder->folderParent,
            'folder_user_id' => $folder->folderUserId,
        ]);

        return $this->toDomain($model);
    }

    public function findByIdForUser(string $id, string $userId): ?Folder
    {
        $model = EloquentFolderModel::query()
            ->where('id', $id)
            ->where('folder_user_id', $userId)
            ->first();

        return $model instanceof EloquentFolderModel ? $this->toDomain($model) : null;
    }

    public function findByUserParentAndName(string $userId, ?string $parentId, string $name): ?Folder
    {
        $query = EloquentFolderModel::query()
            ->where('folder_user_id', $userId)
            ->where('folder_name', $name);

        if ($parentId === null) {
            $query->whereNull('folder_parent');
        } else {
            $query->where('folder_parent', $parentId);
        }

        $model = $query->first();

        return $model instanceof EloquentFolderModel ? $this->toDomain($model) : null;
    }

    public function findAllByUserId(string $userId): array
    {
        $models = EloquentFolderModel::query()
            ->where('folder_user_id', $userId)
            ->orderBy('folder_name')
            ->get();

        return $models->map(fn(EloquentFolderModel $model) => $this->toDomain($model))->all();
    }

    private function toDomain(EloquentFolderModel $model): Folder
    {
        return new Folder(
            id: (string) $model->id,
            folderName: (string) $model->folder_name,
            folderParent: $model->folder_parent === null ? null : (string) $model->folder_parent,
            folderUserId: (string) $model->folder_user_id,
        );
    }
}