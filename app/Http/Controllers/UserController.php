<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SignUpResource;
use App\Http\Resources\SignInResource;
use App\Users\Application\UseCase\SignIn\SignInHandler;
use App\Users\Application\UseCase\SignUp\SignUpHandler;
use App\Users\Application\UseCase\SignIn\SignInCommand;
use App\Users\Application\UseCase\SignUp\SignUpCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function signUp(Request $request, SignUpHandler $handler): JsonResponse
    {
        $validated = $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_password' => ['required', 'string', 'min:8', 'max:255'],
            'user_email' => ['required', 'email', 'max:255'],
        ]);

        $command = new SignUpCommand(
            user_name: $validated['user_name'],
            user_password: $validated['user_password'],
            user_email: $validated['user_email'],
        );

        return response()->json(SignUpResource::toArray($handler->handle($command)), 201);
    }

    public function signIn(Request $request, SignInHandler $handler): JsonResponse
    {
        $validated = $request->validate([
            'user_email' => ['required', 'email', 'max:255'],
            'user_password' => ['required', 'string'],
        ]);

        $command = new SignInCommand(
            user_email: $validated['user_email'],
            user_password: $validated['user_password'],
        );

        return response()->json(SignInResource::toArray($handler->handle($command)));
    }
}
