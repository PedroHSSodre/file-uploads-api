<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/files/presigned-url', [FileController::class, 'getPresignedUrl']);

Route::prefix('user')
    ->controller(UserController::class)
    ->group(function (): void {
        Route::post('/signup', 'signUp');
        Route::post('/signin', 'signIn');
    });

Route::prefix('folders')
    ->middleware('jwt.auth')
    ->controller(FolderController::class)
    ->group(function (): void {
        Route::post('/', 'create');
        Route::get('/', 'list');
        Route::delete('/{id}', 'delete');
    });