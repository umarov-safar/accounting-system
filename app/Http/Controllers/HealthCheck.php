<?php

namespace App\Http\Controllers;

class HealthCheck
{
    public function __invoke()
    {
        return 'OK';
    }
}
