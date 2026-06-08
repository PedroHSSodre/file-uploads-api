<?php

declare(strict_types=1);

namespace App\UserAccess\Infrastructure\Security;

use App\UserAccess\Domain\Entity\User;
use App\UserAccess\Domain\Port\JwtTokenPort;
use JsonException;
use RuntimeException;
use Throwable;

class HmacSha256JwtTokenAdapter implements JwtTokenPort
{
    public function generate(User $user): string
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + ($this->ttlMinutes() * 60);

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'sub' => $user->id,
            'user_email' => $user->userEmail,
            'user_name' => $user->userName,
            'iat' => $issuedAt,
            'exp' => $expiresAt,
        ];

        $encodedHeader = $this->base64UrlEncode($this->jsonEncode($header));
        $encodedPayload = $this->base64UrlEncode($this->jsonEncode($payload));
        $signature = $this->sign($encodedHeader.'.'.$encodedPayload);

        return $encodedHeader.'.'.$encodedPayload.'.'.$signature;
    }

    public function validate(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $signature] = $parts;
        $expectedSignature = $this->sign($encodedHeader.'.'.$encodedPayload);

        if (! hash_equals($expectedSignature, $signature)) {
            return null;
        }

        try {
            $header = $this->jsonDecode($this->base64UrlDecode($encodedHeader));
            $payload = $this->jsonDecode($this->base64UrlDecode($encodedPayload));
        } catch (Throwable) {
            return null;
        }

        if (($header['alg'] ?? null) !== 'HS256' || ! isset($payload['sub'])) {
            return null;
        }

        if (isset($payload['exp']) && (int) $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }

    private function sign(string $content): string
    {
        return $this->base64UrlEncode(hash_hmac('sha256', $content, $this->secret(), true));
    }

    private function secret(): string
    {
        $secret = (string) config('jwt.secret', '');

        if (str_starts_with($secret, 'base64:')) {
            $decodedSecret = base64_decode(substr($secret, 7), true);
            $secret = $decodedSecret === false ? '' : $decodedSecret;
        }

        if ($secret === '') {
            throw new RuntimeException('JWT secret is not configured.');
        }

        return $secret;
    }

    private function ttlMinutes(): int
    {
        return max(1, (int) config('jwt.ttl_minutes', 60));
    }

    /**
     * @param array<string, mixed> $value
     */
    private function jsonEncode(array $value): string
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Unable to encode JWT payload.', previous: $exception);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function jsonDecode(string $value): array
    {
        $decoded = json_decode($value, true, flags: JSON_THROW_ON_ERROR);

        return is_array($decoded) ? $decoded : [];
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $base64 = strtr($value, '-_', '+/');
        $base64 .= str_repeat('=', (4 - strlen($base64) % 4) % 4);
        $decoded = base64_decode($base64, true);

        if ($decoded === false) {
            throw new RuntimeException('Invalid base64url value.');
        }

        return $decoded;
    }
}
