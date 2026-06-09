<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/files/presigned-url', [FileController::class, 'getPresignedUrl']);
Route::post('/user/signup', [UserController::class, 'signUp']);
Route::post('/user/signin', [UserController::class, 'signIn']);
Route::post('/folders', [FolderController::class, 'create'])->middleware('jwt.auth');
