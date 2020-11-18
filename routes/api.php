<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('V1')->prefix('v1')->group(function () {
    Route::get('/test', function (Request $request) {
        return [ "a" => "b"];
    });
});
