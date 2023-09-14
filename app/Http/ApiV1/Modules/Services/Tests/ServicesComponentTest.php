<?php

use App\Domain\Services\Models\Service;
use App\Http\ApiV1\Modules\Services\Tests\Factories\ServiceFactory;
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

test('POST /api/v1/services 201', function () {
    $request = ServiceFactory::new()->make();

    postJson('/api/v1/services',  $request)
        ->assertStatus(201)
        ->assertJsonFragment($request);

    assertDatabaseHas(Service::tableName(), [
        'seller_id' => $request['seller_id'],
        'name' => $request['name'],
    ]);
});

test('POST /api/v1/services 400', function () {
    $request =  ServiceFactory::new()->except(['seller_id', 'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    postJson('/api/v1/services', $request)
        ->assertStatus(400);
});

test('GET /api/v1/services/{id} 200', function () {
    $service = Service::factory()->create();

    getJson("/api/v1/services/$service->id")
        ->assertStatus(200)
        ->assertJsonFragment($service->toArray());
});

test('GET /api/v1/services/{id} 404', function () {
    getJson('/api/v1/services/11111')
        ->assertStatus(404);
});

test('PUT /api/v1/services/{id} 200', function () {

    $service = Service::factory()->create();
    $request = ServiceFactory::new()->make();

    putJson("/api/v1/services/$service->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseHas(Service::tableName(), [
        'id' => $service->getKey(),
        'seller_id' => $request['seller_id'],
        'name' => $request['name'],
    ]);

});

test('PUT /api/v1/services/{id} 400', function () {

    $service = Service::factory()->create();
    $request =  ServiceFactory::new()->except(['seller_id', 'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    putJson("/api/v1/services/$service->id", $request)
        ->assertStatus(400);
});

test('PUT /api/v1/services/{id} 404', function () {
    $service = Service::factory()->create();
    $request =  ServiceFactory::new()->make();
    $this->skipNextOpenApiRequestValidation();

    putJson("/api/v1/services/1000", $request)
        ->assertStatus(404);
});

test('DELETE /api/v1/services/{id} 200', function () {
    $service = Service::factory()->create();

    deleteJson("/api/v1/services/$service->id")
        ->assertStatus(200);

    assertSoftDeleted(Service::tableName(), [
        'id' => $service->id
    ]);
});

test('PATCH /api/v1/services/{id} 200', function () {
    $service= Service::factory()->create();
    $request =  ServiceFactory::new()->except(['seller_id', 'name'])->make();

    $this->skipNextOpenApiRequestValidation();
    patchJson("/api/v1/services/$service->id", $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseHas(Service::tableName(), [
        'id' => $service->id,
        'name' => $service->name,
        'base_price' => $request['base_price']
    ]);
});

test('PATCH /api/v1/services/{id} 404', function () {
    $request =  ServiceFactory::new()->make();
    patchJson('/api/v1/services/1000', $request)
        ->assertStatus(404);
});


test('POST /api/v1/services:mass-delete 200', function () {
    $services = Service::factory()->count(5)->create();

    postJson('/api/v1/services:mass-delete', ['ids' => $services->pluck('id')->toArray()])
        ->assertStatus(200);

    $services->each(fn(Service $service) => assertSoftDeleted(Service::tableName(), ['id' => $service->id]));
});

test('POST /api/v1/services:mass-delete 400', function () {
    postJson('/api/v1/services:mass-delete', ['ids' => []])
        ->assertStatus(400);
});

test('POST /api/v1/services:search 200', function (
    $key, $value, $filterKey = null, $filterValue = null
) {
    $service = Service::factory()->create([$key => $value]);
    Service::factory()->count(3)->create();
    $filter = [
        'filter' => [
            ($filterKey ?: $key) => ($filterValue ?: $value)
        ]
    ];

    postJson('/api/v1/services:search', $filter)
        ->assertStatus(200)
        ->assertJsonPath('data.0.id', $service->getKey());
})->with([
    ['seller_id', 33],
    ['name', 'Service test'],
    ['name', 'Service with like', 'name_like', 'with'],
    ['description', 'some description is in this service', 'description_like', 'some description'],
    ['base_price', 100, 'base_price_gte', 99],
    ['base_price', 100, 'base_price_lte', 101],
]);

test('POST /api/v1/services:search-one 200', function (
    $key, $value, $filterKey = null, $filterValue = null
) {
    $service = Service::factory()->create([$key => $value]);
    Service::factory()->count(3)->create();
    $filter = [
        'filter' => [
            ($filterKey ?: $key) => ($filterValue ?: $value)
        ]
    ];

    postJson('/api/v1/services:search-one', $filter)
        ->assertStatus(200)
        ->assertJsonPath('data.id', $service->getKey());
})->with([
    ['seller_id', 33],
    ['name', 'Service test'],
    ['name', 'Service with like', 'name_like', 'with'],
    ['base_price', 100, 'base_price_gte', 99],
    ['base_price', 100, 'base_price_lte', 101],
]);

