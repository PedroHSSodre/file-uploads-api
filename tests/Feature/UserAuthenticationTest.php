<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('jwt.secret', 'testing-jwt-secret');
        Config::set('jwt.ttl_minutes', 60);
    }

    public function test_user_can_sign_up(): void
    {
        $response = $this->postJson('/api/user/signup', [
            'user_name' => 'Pedro',
            'user_password' => 'password123',
            'user_email' => 'pedro@example.com',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'id',
                'user_name',
                'user_email',
            ])
            ->assertJson([
                'user_name' => 'Pedro',
                'user_email' => 'pedro@example.com',
            ])
            ->assertJsonMissing([
                'user_password' => 'password123',
            ]);

        $storedUser = DB::table('users')
            ->where('user_email', 'pedro@example.com')
            ->first();

        $this->assertNotNull($storedUser);
        $this->assertNotSame('password123', $storedUser->user_password);
        $this->assertTrue(Hash::check('password123', $storedUser->user_password));
    }

    public function test_user_cannot_sign_up_with_existing_email(): void
    {
        $payload = [
            'user_name' => 'Pedro',
            'user_password' => 'password123',
            'user_email' => 'pedro@example.com',
        ];

        $this->postJson('/api/user/signup', $payload)->assertCreated();

        $this->postJson('/api/user/signup', $payload)
            ->assertConflict()
            ->assertJson([
                'message' => 'User email already exists.',
            ]);
    }

    public function test_user_can_sign_in(): void
    {
        $this->postJson('/api/user/signup', [
            'user_name' => 'Pedro',
            'user_password' => 'password123',
            'user_email' => 'pedro@example.com',
        ])->assertCreated();

        $response = $this->postJson('/api/user/signin', [
            'user_email' => 'pedro@example.com',
            'user_password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'user' => [
                    'user_name' => 'Pedro',
                    'user_email' => 'pedro@example.com',
                ],
            ])
            ->assertJsonStructure([
                'access_token',
                'user' => [
                    'id',
                    'user_name',
                    'user_email',
                ],
            ]);
    }

    public function test_user_cannot_sign_in_with_invalid_credentials(): void
    {
        $this->postJson('/api/user/signup', [
            'user_name' => 'Pedro',
            'user_password' => 'password123',
            'user_email' => 'pedro@example.com',
        ])->assertCreated();

        $this->postJson('/api/user/signin', [
            'user_email' => 'pedro@example.com',
            'user_password' => 'wrong-password',
        ])
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_authenticated_route_can_be_accessed_with_bearer_token(): void
    {
        Route::middleware('jwt.auth')->get('/testing/authenticated-user', function (Request $request) {
            return response()->json($request->user()->toPublicArray());
        });

        $this->postJson('/api/user/signup', [
            'user_name' => 'Pedro',
            'user_password' => 'password123',
            'user_email' => 'pedro@example.com',
        ])->assertCreated();

        $token = $this->postJson('/api/user/signin', [
            'user_email' => 'pedro@example.com',
            'user_password' => 'password123',
        ])->json('access_token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/testing/authenticated-user')
            ->assertOk()
            ->assertJson([
                'user_name' => 'Pedro',
                'user_email' => 'pedro@example.com',
            ]);
    }

    public function test_authenticated_route_rejects_missing_bearer_token(): void
    {
        Route::middleware('jwt.auth')->get('/testing/protected-route', fn () => response()->json([
            'message' => 'ok',
        ]));

        $this->getJson('/testing/protected-route')
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
