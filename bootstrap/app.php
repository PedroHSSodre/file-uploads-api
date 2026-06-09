<?php

use App\Folders\Domain\Exception\FolderAlreadyExistsException;
use App\Folders\Domain\Exception\FolderNotFoundException;
use App\Folders\Domain\Exception\FolderParentNotFoundException;
use App\Folders\Domain\Exception\InvalidFolderNameException;
use App\Http\Middleware\JwtAuthenticateMiddleware;
use App\Users\Domain\Exception\InvalidCredentialsException;
use App\Users\Domain\Exception\UserAlreadyExistsException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => JwtAuthenticateMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (FolderParentNotFoundException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        });

        $exceptions->render(function (FolderAlreadyExistsException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        });

        $exceptions->render(function (InvalidFolderNameException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        });

        $exceptions->render(function (InvalidCredentialsException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 401);
        });

        $exceptions->render(function (UserAlreadyExistsException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        });

        $exceptions->render(function (FolderNotFoundException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        });
    })->create();
