<?php

use Illuminate\Support\Facades\Route;

Route::prefix('foos')->group(function () {
    require_once "Modules/Foos/routes.php";
});
