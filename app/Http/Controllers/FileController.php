<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Files\Application\UseCase\GetPresignedUrl\GetPresignedUrlHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController
{
    public function getPresignedUrl(Request $request, GetPresignedUrlHandler $handler): JsonResponse
    {
        $validated = $request->validate([
            'path' => ['required', 'string'],
        ]);

        return response()->json($handler->handle($validated));
    }
}
