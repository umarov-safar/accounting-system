<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Healthcheck test
     *
     * @return void
     */
    public function testHealthTest()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
    }
}
