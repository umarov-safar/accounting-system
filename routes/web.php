<?php

use App\Http\Controllers\HealthCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('health', HealthCheck::class);

