<?php

namespace App\Http\Controllers;

class HealthCheck extends Controller
{
    public function __invoke()
    {
        return 'OK';
    }
}
