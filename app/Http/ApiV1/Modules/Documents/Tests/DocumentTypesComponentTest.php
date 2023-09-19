<?php

use App\Http\ApiV1\Support\Tests\ApiV1ComponentTestCase;

use function Pest\Laravel\getJson;

uses(ApiV1ComponentTestCase::class);
uses()->group('component');

test('GET /api/v1/document-types:store 200', function () {
    getJson('/api/v1/document-types:store')
        ->assertStatus(200);
});

test('GET /api/v1/document-types:store 400', function () {
    getJson('/api/v1/document-types:store')
        ->assertStatus(400);
});

test('GET /api/v1/document-types:store 404', function () {
    getJson('/api/v1/document-types:store')
        ->assertStatus(404);
});

test('GET /api/v1/document-types:finance 200', function () {
    getJson('/api/v1/document-types:finance')
        ->assertStatus(200);
});

test('GET /api/v1/document-types:finance 400', function () {
    getJson('/api/v1/document-types:finance')
        ->assertStatus(400);
});

test('GET /api/v1/document-types:finance 404', function () {
    getJson('/api/v1/document-types:finance')
        ->assertStatus(404);
});
