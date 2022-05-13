<?php

use App\Http\Web\Controllers\HealthCheck;
use App\Http\Web\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('health', HealthCheck::class);

Route::get('/', [SwaggerController::class, 'listSwaggers']);
