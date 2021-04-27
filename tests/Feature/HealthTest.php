<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthTest extends TestCase
{
    /**
     * Healthcheck test
     *
     * @return void
     */
    public function test_health()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
    }
}
