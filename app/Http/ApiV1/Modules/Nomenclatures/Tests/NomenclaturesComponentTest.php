<?php

use App\Domain\Nomenclatures\Models\Nomenclature;
use App\Http\ApiV1\Modules\Nomenclatures\Tests\Factories\NomenclatureFactory;
use App\Http\ApiV1\Support\Tests\ApiV1ComponentTestCase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(ApiV1ComponentTestCase::class);
uses()->group('component');

test('POST /api/v1/nomenclatures 201', function () {
    $request = NomenclatureFactory::new()->make();
    postJson('/api/v1/nomenclatures', $request)
        ->assertStatus(201)
        ->assertJsonFragment($request);

    assertDatabaseHas(Nomenclature::tableName(), [
        'seller_id' => $request['seller_id'],
        'obj_type' => $request['obj_type'],
        'obj_id' => $request['obj_id'],
    ]);
});

test('POST /api/v1/nomenclatures 400 unique seller_id, obj_id and obj_type', function () {
    $nomenclature= Nomenclature::factory()->create();
    $request = NomenclatureFactory::new()->make([
        'seller_id' => $nomenclature->seller_id,
        'obj_id' => $nomenclature->obj_id,
        'obj_type' => $nomenclature->obj_type
        ]);

    postJson('/api/v1/nomenclatures', $request)
        ->assertStatus(400);
});

test('POST /api/v1/nomenclatures 400 required fields', function () {
    $request = NomenclatureFactory::new()->except(['seller_id', 'obj_id'])->make();
    $this->skipNextOpenApiRequestValidation();

    postJson('/api/v1/nomenclatures', $request)
        ->assertStatus(400)
        ->assertJsonCount(2, 'errors');
});

test('GET /api/v1/nomenclatures/{id} 200', function () {
    $nomenclature = Nomenclature::factory()->create();

    getJson("/api/v1/nomenclatures/$nomenclature->id")
        ->assertStatus(200)
        ->assertJsonFragment($nomenclature->toArray());
});

test('GET /api/v1/nomenclatures/{id} 404', function () {
    getJson('/api/v1/nomenclatures/1000')
        ->assertStatus(404);
});

test('PUT /api/v1/nomenclatures/{id} 200', function () {
    $nomenclature = Nomenclature::factory()->create();
    $request = NomenclatureFactory::new()->make();

    putJson("/api/v1/nomenclatures/$nomenclature->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseHas(Nomenclature::tableName(), [
        'id' => $nomenclature->getKey(),
        'seller_id' => $request['seller_id'],
        'base_price' => $request['base_price'],
        'obj_type' => $request['obj_type'],
    ]);
});

test('PUT /api/v1/nomenclatures/{id} 400', function () {
    $nomenclature= Nomenclature::factory()->create();
    $request =  NomenclatureFactory::new()->except(['seller_id', 'obj_id'])->make();
    $this->skipNextOpenApiRequestValidation();

    putJson("/api/v1/nomenclatures/$nomenclature->id", $request)
        ->assertStatus(400);
});

test('PUT /api/v1/nomenclatures/{id} 404', function () {
    putJson('/api/v1/nomenclatures/1000', NomenclatureFactory::new()->make())
        ->assertStatus(404);
});

test('DELETE /api/v1/nomenclatures/{id} 200', function () {
    $nomenclature= Nomenclature::factory()->create();

    deleteJson("/api/v1/nomenclatures/$nomenclature->id")
        ->assertStatus(200);

    assertSoftDeleted(Nomenclature::tableName(), [
        'id' => $nomenclature->id
    ]);
});


test('PATCH /api/v1/nomenclatures/{id} 200', function () {

    $nomenclature= Nomenclature::factory()->create();
    $request =  NomenclatureFactory::new()->except(['obj_type', 'obj_id'])->make();

    patchJson("/api/v1/nomenclatures/$nomenclature->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseHas(Nomenclature::tableName(), [
        'id' => $nomenclature->id,
        'base_price' => $request['base_price']
    ]);
});

test('PATCH /api/v1/nomenclatures/{id} 400', function () {

    $nomenclature= Nomenclature::factory()->create();
    $nomenclature2 = Nomenclature::factory()->create();
    $request = NomenclatureFactory::new()->make([
        'seller_id' => $nomenclature->seller_id,
        'obj_id' => $nomenclature->obj_id,
        'obj_type' => $nomenclature->obj_type
    ]);

    patchJson("/api/v1/nomenclatures/$nomenclature2->id", $request)
        ->assertStatus(400);
});

test('PATCH /api/v1/nomenclatures/{id} 404', function () {
    $request = NomenclatureFactory::new()->make();

    patchJson('/api/v1/nomenclatures/1000', $request)
        ->assertStatus(404);
});

test('POST /api/v1/nomenclatures:mass-delete 200', function () {
    $noms = Nomenclature::factory()->count(5)->create();

    postJson('/api/v1/nomenclatures:mass-delete', ['ids' => $noms->pluck('id')->toArray()])
        ->assertStatus(200);

    $noms->each(fn(Nomenclature $nom) => assertSoftDeleted(Nomenclature::tableName(), ['id' => $nom->id]));
});

test('POST /api/v1/nomenclatures:mass-delete 400', function () {
    postJson('/api/v1/nomenclatures:mass-delete', ['ids' => []])
        ->assertStatus(400);
});

test('POST /api/v1/nomenclatures:search 200', function () {
    $noms = Nomenclature::factory()->count(10)->create();

    postJson('/api/v1/nomenclatures:search')
        ->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('data.0.id', $noms->first()->id);
});



test('POST /api/v1/nomenclatures:search-one 200', function () {
    $noms = Nomenclature::factory()->count(3)->create();

    postJson('/api/v1/nomenclatures:search-one', [
        'filter' => [
            'seller_id' => $noms->first()->seller_id,
            'obj_id' => $noms->first()->obj_id
        ]
    ])
        ->assertStatus(200)
        ->assertJsonPath('data.id', $noms->first()->id);
});

