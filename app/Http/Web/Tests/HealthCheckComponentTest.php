<?php

use Tests\ComponentTestCase;

uses(ComponentTestCase::class);

test()
    ->get('/health')
    ->assertStatus(200)
    ->assertHeader('content-type', 'text/html; charset=UTF-8');
