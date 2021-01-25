<?php

use App\Http\Web\Controllers\HealthCheck;
use Illuminate\Support\Facades\Route;

Route::get('health', HealthCheck::class);
