<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\UserAccess\Application\UseCase\SignIn\SignInHandler;
use App\UserAccess\Application\UseCase\SignUp\SignUpHandler;
use App\UserAccess\Domain\Exception\InvalidCredentialsException;
use App\UserAccess\Domain\Exception\UserAlreadyExistsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAccessController
{
    public function signUp(Request $request, SignUpHandler $handler): JsonResponse
    {
        $validated = $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_password' => ['required', 'string', 'min:8', 'max:255'],
            'user_email' => ['required', 'email', 'max:255'],
        ]);

        try {
            return response()->json($handler->handle($validated), 201);
        } catch (UserAlreadyExistsException) {
            return response()->json([
                'message' => 'User email already exists.',
            ], 409);
        }
    }

    public function signIn(Request $request, SignInHandler $handler): JsonResponse
    {
        $validated = $request->validate([
            'user_email' => ['required', 'email', 'max:255'],
            'user_password' => ['required', 'string'],
        ]);

        try {
            return response()->json($handler->handle($validated));
        } catch (InvalidCredentialsException) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }
    }
}
