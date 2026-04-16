<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('videos')
    ->name('videos.')
    ->group(static function (): void {
        Route::apiResource('', VideoController::class)
            ->parameter('', 'video_id')
            ->whereNumber('video_id');
    });

Route::get('categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/user', static function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
