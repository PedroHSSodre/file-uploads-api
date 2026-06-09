<?php

namespace App\Providers;

use App\Files\Domain\Port\PresignedUrlPort;
use App\Files\Infrastructure\Storage\LaravelS3PresignedUrlAdapter;
use App\Folders\Domain\Port\FolderRepositoryPort;
use App\Folders\Infrastructure\Persistence\EloquentFolderRepository;
use App\Shared\Domain\Port\UuidGeneratorPort;
use App\Shared\Infrastructure\Identifier\LaravelUuidGeneratorAdapter;
use App\Users\Domain\Port\JwtTokenPort;
use App\Users\Domain\Port\PasswordHasherPort;
use App\Users\Domain\Port\UserRepositoryPort;
use App\Users\Infrastructure\Persistence\EloquentUserRepositoryAdapter;
use App\Users\Infrastructure\Security\HmacSha256JwtTokenAdapter;
use App\Users\Infrastructure\Security\LaravelPasswordHasherAdapter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PresignedUrlPort::class, LaravelS3PresignedUrlAdapter::class);
        $this->app->bind(UserRepositoryPort::class, EloquentUserRepositoryAdapter::class);
        $this->app->bind(PasswordHasherPort::class, LaravelPasswordHasherAdapter::class);
        $this->app->bind(JwtTokenPort::class, HmacSha256JwtTokenAdapter::class);
        $this->app->bind(FolderRepositoryPort::class, EloquentFolderRepository::class);
        $this->app->bind(UuidGeneratorPort::class, LaravelUuidGeneratorAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
