<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Users\Domain\Port\JwtTokenPort;
use App\Users\Domain\Port\UserRepositoryPort;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticateMiddleware
{
    public function __construct(
        private readonly JwtTokenPort $jwtTokenPort,
        private readonly UserRepositoryPort $userRepositoryPort,
    ) {
    }

    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if ($token === null) {
            return $this->unauthorized();
        }

        $payload = $this->jwtTokenPort->validate($token);

        if ($payload === null) {
            return $this->unauthorized();
        }

        $user = $this->userRepositoryPort->findById((string) $payload['sub']);

        if ($user === null) {
            return $this->unauthorized();
        }

        $request->attributes->set('authenticated_user', $user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    private function unauthorized(): JsonResponse
    {
        return response()->json([
            'message' => 'Unauthenticated.',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
