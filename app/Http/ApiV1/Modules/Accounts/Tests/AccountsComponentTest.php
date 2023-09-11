<?php

use App\Domain\Accounts\Models\Account;
use App\Http\ApiV1\Modules\Accounts\Tests\Factories\AccountFactory;
use App\Http\ApiV1\Support\Tests\ApiV1ComponentTestCase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(ApiV1ComponentTestCase::class);
uses()->group('component');

test('POST /api/v1/accounts 201', function () {
    $request = AccountFactory::new()->make();
    postJson('/api/v1/accounts', $request)
        ->assertStatus(201)
        ->assertJsonFragment($request);

    assertDatabaseHas(Account::tableName(), [
        'name' => $request['name'],
        'seller_id' => $request['seller_id']
    ]);
});

test('POST /api/v1/accounts 400', function () {
    $request = AccountFactory::new()->only(['type',  'name'])->make();
    $this->skipNextOpenApiRequestValidation();
    postJson('/api/v1/accounts', $request)
        ->assertStatus(400);
});

test('GET /api/v1/accounts/{id} 200', function () {
    $account = Account::factory()->create();

    getJson('/api/v1/accounts/' . $account->id)
        ->assertStatus(200)
        ->assertJsonFragment($account->toArray());
});

test('GET /api/v1/accounts/{id} 404', function () {
    getJson('/api/v1/accounts/1000')
        ->assertStatus(404);
});

test('PUT /api/v1/accounts/{id} 200', function () {
    $account = Account::factory()->create();
    $request = AccountFactory::new()->make();

    putJson('/api/v1/accounts/' .  $account->id, $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseMissing(Account::tableName(), [
        'name' => $account['name'],
        'seller_id' => $account['seller_id'],
        'type' => $account['type']
    ]);
});

test('PUT /api/v1/accounts/{id} 400', function () {
    $account = Account::factory()->create();
    $request = AccountFactory::new()->only(['type',  'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    putJson('/api/v1/accounts/'.$account->id, $request)
        ->assertStatus(400);
});

test('PUT /api/v1/accounts/{id} 404', function () {
    $account = Account::factory()->create();
    $request = AccountFactory::new()->make();

    putJson('/api/v1/accounts/10000', $request)
        ->assertStatus(404);
});

test('DELETE /api/v1/accounts/{id} 200', function () {
    $account = Account::factory()->create();

    deleteJson('/api/v1/accounts/' . $account->id)
        ->assertStatus(200);

    assertSoftDeleted(Account::tableName(), [
        'id' => $account->id
    ]);
});
;

test('PATCH /api/v1/accounts/{id} 200', function () {
    $account = Account::factory()->create();
    $request = AccountFactory::new()->only(['type',  'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    patchJson('/api/v1/accounts/' .  $account->id, $request)
        ->assertStatus(200)
        ->assertJsonFragment($request);

    assertDatabaseMissing(Account::tableName(), [
        'id' => $account->id,
        'name' => $account['name'],
        'type' => $account['type'],
    ]);
});


test('PATCH /api/v1/accounts/{id} 404', function () {
    $account = Account::factory()->create();
    $request = AccountFactory::new()->only(['type',  'name'])->make();
    $this->skipNextOpenApiRequestValidation();

    patchJson('/api/v1/accounts/10000', $request)
        ->assertStatus(404);
});

//test('POST /api/v1/accounts:mass-delete 200', function () {
//    postJson('/api/v1/accounts:mass-delete')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/accounts:mass-delete 400', function () {
//    postJson('/api/v1/accounts:mass-delete')
//        ->assertStatus(400);
//});
//
//test('POST /api/v1/accounts:search 200', function () {
//    postJson('/api/v1/accounts:search')
//        ->assertStatus(200);
//});
//
//test('POST /api/v1/accounts:search 400', function () {
//    postJson('/api/v1/accounts:search')
//        ->assertStatus(400);
//});
