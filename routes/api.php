<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\UserAccessController;
use Illuminate\Support\Facades\Route;

Route::post('/files/presigned-url', [FileUploadController::class, 'getPresignedUrl']);
Route::post('/user/signup', [UserAccessController::class, 'signUp']);
Route::post('/user/signin', [UserAccessController::class, 'signIn']);
