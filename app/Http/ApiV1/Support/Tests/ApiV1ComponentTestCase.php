<?php

namespace App\Http\ApiV1\Support\Tests;

use Greensight\LaravelOpenApiTesting\ValidatesAgainstOpenApiSpec;
use Tests\ComponentTestCase;

abstract class ApiV1ComponentTestCase extends ComponentTestCase
{
    use ValidatesAgainstOpenApiSpec;

    protected function getOpenApiDocumentPath(): string
    {
        return public_path('api-docs/v1/index.yaml');
    }
}
