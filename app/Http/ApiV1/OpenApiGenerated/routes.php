<?php

/**
 * NOTE: This file is auto generated by OpenAPI Generator.
 * Do NOT edit it manually. Run `php artisan openapi:generate-server`.
 */

use Illuminate\Support\Facades\Route;

Route::get('examples/{id}', [\App\Http\ApiV1\Modules\Foos\Controllers\FoosController::class, 'get'])->name('getExample');