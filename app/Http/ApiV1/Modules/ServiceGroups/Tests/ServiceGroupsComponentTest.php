<?php

use App\Domain\Services\Models\ServiceGroup;
use App\Http\ApiV1\Modules\ServiceGroups\Tests\Factories\ServiceGroupFactory;
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

test('POST /api/v1/service-groups 201', function () {
    $request = ServiceGroupFactory::new()->make();

    postJson('/api/v1/service-groups',  $request)
        ->assertStatus(201)
        ->assertJsonFragment($request);

    assertDatabaseHas(ServiceGroup::tableName(), [
        'seller_id' => $request['seller_id'],
        'name' => $request['name'],
        'parent_id' => $request['parent_id'],
    ]);
});

test('POST /api/v1/service-groups 400', function () {

    $request =  ServiceGroupFactory::new()->except(['seller_id', 'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    postJson('/api/v1/service-groups', $request)
        ->assertStatus(400);
});

test('GET /api/v1/service-groups/{id} 200', function () {

    $servGroup = ServiceGroup::factory()->create();

    getJson("/api/v1/service-groups/$servGroup->id")
        ->assertStatus(200)
        ->assertJsonFragment($servGroup->toArray());
});

test('GET /api/v1/service-groups/{id} 404', function () {
    getJson('/api/v1/service-groups/1000')
        ->assertStatus(404);
});

test('PUT /api/v1/service-groups/{id} 200', function () {
    $servGroup = ServiceGroup::factory()->create();
    $request = ServiceGroupFactory::new()->make();

    putJson("/api/v1/service-groups/$servGroup->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseHas(ServiceGroup::tableName(), [
        'id' => $servGroup->getKey(),
        'seller_id' => $request['seller_id'],
        'name' => $request['name'],
    ]);
});

test('PUT /api/v1/service-groups/{id} 400', function () {
    $servGroup= ServiceGroup::factory()->create();
    $request =  ServiceGroupFactory::new()->except(['seller_id', 'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    putJson("/api/v1/service-groups/$servGroup->id", $request)
        ->assertStatus(400);
});

test('PUT /api/v1/service-groups/{id} 404', function () {
    $request = ServiceGroupFactory::new()->make();

    putJson("/api/v1/service-groups/10000", $request)
        ->assertStatus(404);
});

test('DELETE /api/v1/service-groups/{id} 200', function () {
    $servGroup= ServiceGroup::factory()->create();

    deleteJson("/api/v1/service-groups/$servGroup->id")
        ->assertStatus(200);

    assertSoftDeleted(ServiceGroup::tableName(), [
        'id' => $servGroup->id
    ]);
});


test('PATCH /api/v1/service-groups/{id} 200', function () {

    $servGroup= ServiceGroup::factory()->create();
    $request =  ServiceGroupFactory::new()->except(['seller_id', 'name'])->make();

    $this->skipNextOpenApiRequestValidation();
    patchJson("/api/v1/service-groups/$servGroup->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseHas(ServiceGroup::tableName(), [
        'id' => $servGroup->id,
        'name' => $servGroup->name,
        'sort' => $request['sort'],
        'parent_id' => $request['parent_id']
    ]);
});


test('PATCH /api/v1/service-groups/{id} 404', function () {
    $request =  ServiceGroupFactory::new()->make();
    patchJson('/api/v1/service-groups/1000', $request)
        ->assertStatus(404);
});

test('POST /api/v1/service-groups:mass-delete 200', function () {
    $serGps = ServiceGroup::factory()->count(5)->create();

    postJson('/api/v1/service-groups:mass-delete', ['ids' => $serGps->pluck('id')->toArray()])
        ->assertStatus(200);

    $serGps->each(fn(ServiceGroup $serviceGroup) => assertSoftDeleted(ServiceGroup::tableName(), ['id' => $serviceGroup->id]));
});

test('POST /api/v1/service-groups:mass-delete 400', function () {
    postJson('/api/v1/service-groups:mass-delete', ['ids' => []])
        ->assertStatus(400);
});

test('POST /api/v1/service-groups:search 200', function () {
    $serGps = ServiceGroup::factory()->count(10)->create();

    postJson('/api/v1/service-groups:search')
        ->assertStatus(200);
});

test('POST /api/v1/service-groups:tree 200', function () {
    $serGps = ServiceGroup::factory()->count(10)->create();
    postJson('/api/v1/service-groups:tree')
        ->assertStatus(200);
});

