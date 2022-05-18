<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\{
    UserController,
    ProjectController
};

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::delete('/logout','logout')->middleware('auth:sanctum');
    Route::post('/forgot-password','forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});

Route::controller(UserController::class)->group(function () {
    Route::post('/user', 'store');
});
