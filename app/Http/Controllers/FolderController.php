<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Folders\Application\UseCase\CreateFolder\CreateFolderCommand;
use App\Folders\Application\UseCase\CreateFolder\CreateFolderHandler;
use App\Folders\Application\UseCase\DeleteFolder\DeleteFolderHandler;
use App\Folders\Application\UseCase\ListFolder\ListFolderHandler;
use App\Http\Resources\FolderResource;
use App\Users\Domain\Entity\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FolderController
{
    public function create(Request $request, CreateFolderHandler $handler): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'uuid'],
        ]);

        /** @var User $authenticatedUser */
        $authenticatedUser = $request->attributes->get('authenticated_user');

        $command = new CreateFolderCommand(
            name: $validated['name'],
            parentId: $validated['parent_id'] ?? null,
            userId: $authenticatedUser->id,
        );

        return response()->json(FolderResource::toArray($handler->handle($command)), 201);
    }

    public function list(Request $request, ListFolderHandler $handler): JsonResponse
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = $request->attributes->get('authenticated_user');

        return response()->json(FolderResource::collection($handler->handle($authenticatedUser->id)), 200);
    }

    public function delete(Request $request, DeleteFolderHandler $handler): JsonResponse
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = $request->attributes->get('authenticated_user');

        $handler->handle($request->route('id'), $authenticatedUser->id);

        return response()->json(null, 204);
    }
}