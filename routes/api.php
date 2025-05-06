<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;


Route::group(['prefix' => 'v1'], function () {

    // login route
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.forgot-password');


    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('api.dashboard.index');

    

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', function (Request $request) {
            $user = $request->user();
            $user->profile_image_url = $user->gravatar;
            return $user;
        });


        Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');

    });
});