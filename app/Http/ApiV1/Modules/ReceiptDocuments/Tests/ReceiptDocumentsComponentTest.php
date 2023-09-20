<?php

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\Modules\ReceiptDocuments\Tests\Factories\ReceiptDocumentsFactory;
use App\Http\ApiV1\Support\Tests\ApiV1ComponentTestCase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(ApiV1ComponentTestCase::class);
uses()->group('component');

test('POST /api/v1/receipt-documents 201', function () {
    $request = ReceiptDocumentsFactory::new()->make();

    $id = postJson('/api/v1/receipt-documents', $request)
        ->assertStatus(201)
        ->assertJsonFragment($request)
        ->json('data.id');

    assertDatabaseHas(ReceiptDocument::tableName(), [
        'id' => $id
    ]);

});

//test('POST /api/v1/receipt-documents 400', function () {
//    postJson('/api/v1/receipt-documents')
//        ->assertStatus(400);
//});
//
//test('GET /api/v1/receipt-documents/{id} 200', function () {
//    getJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(200);
//});
//
//test('GET /api/v1/receipt-documents/{id} 404', function () {
//    getJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(404);
//});
//
//test('PUT /api/v1/receipt-documents/{id} 200', function () {
//    putJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(200);
//});
//
//test('PUT /api/v1/receipt-documents/{id} 400', function () {
//    putJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(400);
//});
//
//test('PUT /api/v1/receipt-documents/{id} 404', function () {
//    putJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(404);
//});
//
//test('DELETE /api/v1/receipt-documents/{id} 200', function () {
//    deleteJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(200);
//});
//
//test('DELETE /api/v1/receipt-documents/{id} 400', function () {
//    deleteJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(400);
//});
//
//test('DELETE /api/v1/receipt-documents/{id} 404', function () {
//    deleteJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(404);
//});
//
//test('PATCH /api/v1/receipt-documents/{id} 200', function () {
//    patchJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(200);
//});
//
//test('PATCH /api/v1/receipt-documents/{id} 400', function () {
//    patchJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(400);
//});
//
//test('PATCH /api/v1/receipt-documents/{id} 404', function () {
//    patchJson('/api/v1/receipt-documents/{id}')
//        ->assertStatus(404);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-fix 200', function () {
//    post('/api/v1/receipt-documents/{id}:set-fix')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-fix 404', function () {
//    post('/api/v1/receipt-documents/{id}:set-fix')
//        ->assertStatus(404);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-fix 400', function () {
//    post('/api/v1/receipt-documents/{id}:set-fix')
//        ->assertStatus(400);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-cancel 200', function () {
//    postJson('/api/v1/receipt-documents/{id}:set-cancel')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-cancel 400', function () {
//    postJson('/api/v1/receipt-documents/{id}:set-cancel')
//        ->assertStatus(400);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-cancel 404', function () {
//    postJson('/api/v1/receipt-documents/{id}:set-cancel')
//        ->assertStatus(404);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-draft 200', function () {
//    postJson('/api/v1/receipt-documents/{id}:set-draft')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-draft 400', function () {
//    postJson('/api/v1/receipt-documents/{id}:set-draft')
//        ->assertStatus(400);
//});
//
//test('POST /api/v1/receipt-documents/{id}:set-draft 404', function () {
//    postJson('/api/v1/receipt-documents/{id}:set-draft')
//        ->assertStatus(404);
//});
//
//test('POST /api/v1/receipt-documents:search 200', function () {
//    postJson('/api/v1/receipt-documents:search')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/receipt-documents:search 400', function () {
//    postJson('/api/v1/receipt-documents:search')
//        ->assertStatus(400);
//});
//
//test('POST /api/v1/receipt-documents:search-one 200', function () {
//    postJson('/api/v1/receipt-documents:search-one')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/receipt-documents:search-one 400', function () {
//    postJson('/api/v1/receipt-documents:search-one')
//        ->assertStatus(400);
//});
