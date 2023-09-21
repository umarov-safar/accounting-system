<?php

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\Modules\ReceiptDocuments\Tests\Factories\ReceiptDocumentsFactory;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentFinanceTypeIdEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
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

test('POST /api/v1/receipt-documents 400', function () {
    $request = ReceiptDocumentsFactory::new()->except(['seller_id'])->make();
    $this->skipNextOpenApiRequestValidation();
    postJson('/api/v1/receipt-documents', $request)
        ->assertStatus(400);
});


test('GET /api/v1/receipt-documents/{id} 200', function () {
    $model = ReceiptDocument::factory()->create();
    getJson("/api/v1/receipt-documents/$model->id")
        ->assertStatus(200);
});


test('GET /api/v1/receipt-documents/{id} 404 when document type is not receipt', function () {
    $model = ReceiptDocument::factory()
        ->state(['document_type_id' => DocumentStoreTypeIdEnum::SALE])
        ->create();

    getJson("/api/v1/receipt-documents/$model->id")
        ->assertStatus(404);
});

test('GET /api/v1/receipt-documents/{id} 404 not model at all', function () {
    getJson("/api/v1/receipt-documents/1000")
        ->assertStatus(404);

});

test('PUT /api/v1/receipt-documents/{id} 200', function () {
    $model = ReceiptDocument::factory()
        ->state(['status' => DocumentStatusEnum::DRAFT])
        ->create();
    $request = ReceiptDocumentsFactory::new()->make();

    $data = putJson("/api/v1/receipt-documents/$model->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request)
        ->json('data');

    assertDatabaseHas(ReceiptDocument::tableName(), [
        'status' => $request['status'],
        'seller_id' => $request['seller_id'],
        'summa' => $request['summa'],
        'id' => $model->id
    ]);
});

test('PUT /api/v1/receipt-documents/{id} 400', function () {
    $model = ReceiptDocument::factory()->create();
    $request = ReceiptDocumentsFactory::new()->except(['seller_id', 'status'])->make();

    $this->skipNextOpenApiRequestValidation();

    putJson("/api/v1/receipt-documents/$model->id", $request)
        ->assertStatus(400);
});

test('PUT /api/v1/receipt-documents/{id} 404', function () {
    $request = ReceiptDocumentsFactory::new()->make();
    putJson("/api/v1/receipt-documents/1000", $request)
        ->assertStatus(404);
});

test('PUT /api/v1/receipt-documents/{id} 500 status must be draft', function () {
    $model = ReceiptDocument::factory()->create(['status' => DocumentStatusEnum::FIX]);
    $request = ReceiptDocumentsFactory::new()->make();

    putJson("/api/v1/receipt-documents/$model->id", $request)
        ->assertStatus(500);
});

//test('DELETE /api/v1/receipt-documents/{id} 200', function () {
//
//    $model = ReceiptDocument::factory()->create(['status' => DocumentStatusEnum::DRAFT]);
//
//    deleteJson("/api/v1/receipt-documents/$model->id")
//        ->assertStatus(200);
//});


test('PATCH /api/v1/receipt-documents/{id} 200', function () {
    $model = ReceiptDocument::factory()
        ->state(['status' => DocumentStatusEnum::DRAFT])
        ->create();
    $request = ReceiptDocumentsFactory::new()->make();

    $data = patchJson("/api/v1/receipt-documents/$model->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request)
        ->json('data');

    assertDatabaseHas(ReceiptDocument::tableName(), [
        'status' => $request['status'],
        'seller_id' => $data['seller_id'],
        'summa' => $data['summa'],
        'id' => $model->id
    ]);
});


test('PATCH /api/v1/receipt-documents/{id} 404', function () {
    $model = ReceiptDocument::factory()
        ->state(['status' => DocumentStatusEnum::DRAFT])
        ->create();
    $request = ReceiptDocumentsFactory::new()->make();

    patchJson("/api/v1/receipt-documents/1000", $request)
        ->assertStatus(404);
});


test('PATCH /api/v1/receipt-documents/{id} 500 status must be draft', function () {
    $model = ReceiptDocument::factory()->create(['status' => DocumentStatusEnum::FIX]);
    $request = ReceiptDocumentsFactory::new()->make();

    patchJson("/api/v1/receipt-documents/$model->id", $request)
        ->assertStatus(500);
});

test('POST /api/v1/receipt-documents/{id}:set-fix 200', function () {
    $model = ReceiptDocument::factory()->create(['status' => DocumentStatusEnum::DRAFT]);

    post("/api/v1/receipt-documents/$model->id:set-fix")
        ->assertStatus(200);

    assertDatabaseHas(ReceiptDocument::tableName(), [
        'status' => DocumentStatusEnum::FIX->value,
        'id' => $model->id
    ]);
});

test('POST /api/v1/receipt-documents/{id}:set-fix 404', function () {
    post('/api/v1/receipt-documents/1000:set-fix')
        ->assertStatus(404);
});

test('POST /api/v1/receipt-documents/{id}:set-cancel 200 status from draft to cancel', function () {
    $model = ReceiptDocument::factory()->create(['status' => DocumentStatusEnum::DRAFT]);

    post("/api/v1/receipt-documents/$model->id:set-cancel")
        ->assertStatus(200);

    assertDatabaseHas(ReceiptDocument::tableName(), [
        'status' => DocumentStatusEnum::CANCEL->value,
        'id' => $model->id
    ]);
});


test('POST /api/v1/receipt-documents/{id}:set-cancel 404', function () {
    postJson('/api/v1/receipt-documents/1000:set-cancel')
        ->assertStatus(404);
});

test('POST /api/v1/receipt-documents/{id}:set-draft 200', function () {

    $model = ReceiptDocument::factory()->create(['status' => DocumentStatusEnum::CANCEL]);

    post("/api/v1/receipt-documents/$model->id:set-draft")
        ->assertStatus(200);

    assertDatabaseHas(ReceiptDocument::tableName(), [
        'status' => DocumentStatusEnum::DRAFT->value,
        'id' => $model->id
    ]);
});


test('POST /api/v1/receipt-documents/{id}:set-draft 404', function () {
    postJson('/api/v1/receipt-documents/2000:set-draft')
        ->assertStatus(404);
});

test('POST /api/v1/receipt-documents:search 200', function (
    string $fieldKey,
    mixed $fieldValue,
    string $filterKey = null,
    mixed $filterValue = null,
) {
    ReceiptDocument::factory()->count(2)->create();
    $model = ReceiptDocument::factory()->create([$fieldKey => $fieldValue]);

    $filter = [
        'filter' => [
            ($filterKey ?? $fieldKey) => ($filterValue ?? $fieldValue)
        ]
    ];

    postJson('/api/v1/receipt-documents:search', $filter)
        ->assertStatus(200)
        ->assertJsonPath('data.0.id', $model->id);
})->with([
    ['seller_id', 1010],
    ['document_date', '2022-10-12'],
    ['document_date', '2024-10-12', 'document_date_gte', '2024-10-11'],
    ['store_id', 1111],
    ['parent_id', 20],
]);

 test('POST /api/v1/receipt-documents:search-one 200', function (
     string $fieldKey,
     mixed $fieldValue,
     string $filterKey = null,
     mixed $filterValue = null,
 ) {
     ReceiptDocument::factory()->count(2)->create();
     $model = ReceiptDocument::factory()->create([$fieldKey => $fieldValue]);

     $filter = [
         'filter' => [
             ($filterKey ?? $fieldKey) => ($filterValue ?? $fieldValue)
         ]
     ];

     postJson('/api/v1/receipt-documents:search', $filter)
         ->assertStatus(200)
         ->assertJsonPath('data.0.id', $model->id);
})->with([
     ['seller_id', 100],
     ['document_date', '2022-10-12'],
     ['document_date', '2022-10-12', 'document_date_gte', '2022-10-11'],
     ['store_id', 11],
     ['parent_id', 20],
 ]);

