<?php

use App\Domain\Support\Concerns\Tests\Stubs\AppliesToAggregateAction;
use App\Domain\Support\Concerns\Tests\Stubs\AppliesToAggregateRoot;
use Ensi\LaravelAuditing\Facades\Transaction;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\travel;

use Tests\IntegrationTestCase;

uses(IntegrationTestCase::class)->group('support', 'integration');

beforeEach(function () {
    Schema::create(AppliesToAggregateRoot::TABLE_NAME, function (Blueprint $table) {
        $table->temporary();
        $table->id();
        $table->timestamps();
        $table->string('name')->nullable();
    });
});

test('apply returns action result', function () {
    $root = AppliesToAggregateRoot::create(['name' => 'foo']);
    $action = fn (AppliesToAggregateRoot $model) => $model->name;

    expect(AppliesToAggregateAction::executeApply($root, $action))->toBe('foo');
});

test('apply uses current transaction', function () {
    $root = AppliesToAggregateRoot::create(['name' => 'foo']);
    $action = fn () => Transaction::uid();

    $result = AppliesToAggregateAction::executeApply($root, $action);

    expect($result)->toBe(Transaction::uid());
});

test('updateOrCreate new model', function () {
    $action = fn (AppliesToAggregateRoot $model) => $model->name = 'foo';

    $entity = AppliesToAggregateAction::executeUpdateOrCreate(null, $action);

    assertDatabaseHas(
        $entity->getTable(),
        ['id' => $entity->id, 'name' => 'foo']
    );
});

test('updateOrCreate existing model', function () {
    $root = AppliesToAggregateRoot::create(['name' => 'foo']);
    $action = fn (AppliesToAggregateRoot $model) => $model->name = 'bar';

    $entity = AppliesToAggregateAction::executeUpdateOrCreate($root, $action);

    expect($entity->getKey())->toBe($root->getKey());
    assertDatabaseHas(
        $root->getTable(),
        ['id' => $root->id, 'name' => 'bar']
    );
});

test('updateOrCreate not saves unchanged model', function () {
    $root = AppliesToAggregateRoot::create(['name' => 'foo']);
    $action = fn (AppliesToAggregateRoot $model) => $model->name = 'foo';
    travel(1)->minutes();

    AppliesToAggregateAction::executeUpdateOrCreate($root, $action);

    assertDatabaseHas(
        $root->getTable(),
        ['id' => $root->id, 'name' => $root->name, 'updated_at' => $root->updated_at]
    );
});

test('delete', function () {
    $root = AppliesToAggregateRoot::create(['name' => 'foo']);

    AppliesToAggregateAction::executeDelete($root);

    assertModelMissing($root);
});

test('delete calls action before', function () {
    $root = AppliesToAggregateRoot::create(['name' => 'foo']);
    $action = fn () => throw new RuntimeException('Action called');

    expect(fn () => AppliesToAggregateAction::executeDelete($root, $action))
        ->toThrow(RuntimeException::class, 'Action called');

    assertDatabaseHas($root->getTable(), ['id' => $root->id]);
});
