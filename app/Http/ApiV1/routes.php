<?php

use Illuminate\Support\Facades\Route;

Route::prefix('foos')->group(__DIR__ . "/Modules/Foos/routes.php");
