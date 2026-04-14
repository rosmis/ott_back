<?php

declare(strict_types=1);

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('videos', VideoController::class);
