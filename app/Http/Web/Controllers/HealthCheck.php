<?php

namespace App\Http\Web\Controllers;

class HealthCheck
{
    public function __invoke()
    {
        return 'OK';
    }
}
