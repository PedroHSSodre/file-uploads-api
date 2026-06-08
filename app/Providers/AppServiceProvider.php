<?php

namespace App\Providers;

use App\FileUpload\Domain\Port\PresignedUrlPort;
use App\FileUpload\Infrastructure\Storage\LaravelS3PresignedUrlAdapter;
use App\UserAccess\Domain\Port\JwtTokenPort;
use App\UserAccess\Domain\Port\PasswordHasherPort;
use App\UserAccess\Domain\Port\UserRepositoryPort;
use App\UserAccess\Infrastructure\Persistence\EloquentUserRepositoryAdapter;
use App\UserAccess\Infrastructure\Security\HmacSha256JwtTokenAdapter;
use App\UserAccess\Infrastructure\Security\LaravelPasswordHasherAdapter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryPort::class, EloquentUserRepositoryAdapter::class);
        $this->app->bind(PasswordHasherPort::class, LaravelPasswordHasherAdapter::class);
        $this->app->bind(JwtTokenPort::class, HmacSha256JwtTokenAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
