<?php

use App\Http\ApiV1\Support\Tests\ApiV1ComponentTestCase;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(ApiV1ComponentTestCase::class);
uses()->group('component');

test('POST /api/v1/services 201', function () {
    postJson('/api/v1/services')
        ->assertStatus(201);
});

test('POST /api/v1/services 400', function () {
    postJson('/api/v1/services')
        ->assertStatus(400);
});

test('GET /api/v1/services/{id} 200', function () {
    getJson('/api/v1/services/{id}')
        ->assertStatus(200);
});

test('GET /api/v1/services/{id} 404', function () {
    getJson('/api/v1/services/{id}')
        ->assertStatus(404);
});

test('PUT /api/v1/services/{id} 200', function () {
    putJson('/api/v1/services/{id}')
        ->assertStatus(200);
});

test('PUT /api/v1/services/{id} 400', function () {
    putJson('/api/v1/services/{id}')
        ->assertStatus(400);
});

test('PUT /api/v1/services/{id} 404', function () {
    putJson('/api/v1/services/{id}')
        ->assertStatus(404);
});

test('DELETE /api/v1/services/{id} 200', function () {
    deleteJson('/api/v1/services/{id}')
        ->assertStatus(200);
});

test('DELETE /api/v1/services/{id} 400', function () {
    deleteJson('/api/v1/services/{id}')
        ->assertStatus(400);
});

test('DELETE /api/v1/services/{id} 404', function () {
    deleteJson('/api/v1/services/{id}')
        ->assertStatus(404);
});

test('PATCH /api/v1/services/{id} 200', function () {
    patchJson('/api/v1/services/{id}')
        ->assertStatus(200);
});

test('PATCH /api/v1/services/{id} 400', function () {
    patchJson('/api/v1/services/{id}')
        ->assertStatus(400);
});

test('PATCH /api/v1/services/{id} 404', function () {
    patchJson('/api/v1/services/{id}')
        ->assertStatus(404);
});

test('POST /api/v1/service:mass-delete 200', function () {
    postJson('/api/v1/service:mass-delete')
        ->assertStatus(200);
});

test('POST /api/v1/service:mass-delete 400', function () {
    postJson('/api/v1/service:mass-delete')
        ->assertStatus(400);
});

test('POST /api/v1/services:search 200', function () {
    postJson('/api/v1/services:search')
        ->assertStatus(200);
});

test('POST /api/v1/services:search 400', function () {
    postJson('/api/v1/services:search')
        ->assertStatus(400);
});

test('POST /api/v1/services:search-one 200', function () {
    postJson('/api/v1/services:search-one')
        ->assertStatus(200);
});

test('POST /api/v1/services:search-one 400', function () {
    postJson('/api/v1/services:search-one')
        ->assertStatus(400);
});
