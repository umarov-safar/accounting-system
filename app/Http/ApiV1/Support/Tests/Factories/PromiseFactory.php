<?php

namespace App\Http\ApiV1\Support\Tests\Factories;

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;

class PromiseFactory
{
    public static function make($response): PromiseInterface
    {
        return Create::promiseFor($response);
    }
}
