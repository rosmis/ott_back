<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:3,1');
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('throttle:3,1');
