<?php

use App\Domain\Support\Concerns\Tests\Stubs\AppliesToDrivenEntityAction;
use App\Domain\Support\Concerns\Tests\Stubs\AppliesToDrivenEntityEntity;
use App\Domain\Support\Concerns\Tests\Stubs\AppliesToDrivenEntityRoot;
use Illuminate\Database\Schema\Blueprint;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\travel;

use Tests\IntegrationTestCase;

uses(IntegrationTestCase::class)->group('support', 'integration');

beforeEach(function () {
    Schema::create(AppliesToDrivenEntityRoot::TABLE_NAME, function (Blueprint $table) {
        $table->temporary();
        $table->id();
        $table->timestamps();
    });

    Schema::create(AppliesToDrivenEntityEntity::TABLE_NAME, function (Blueprint $table) {
        $table->temporary();
        $table->id();
        $table->timestamps();

        $table->string('name')->nullable();
        $table->unsignedBigInteger('parent_id');
    });
});

test('create driven model', function () {
    $root = AppliesToDrivenEntityRoot::create([]);
    $action = fn (AppliesToDrivenEntityEntity $entity, AppliesToDrivenEntityRoot $root) => $entity->name = 'foo';

    $result = AppliesToDrivenEntityAction::executeCreate($root, $action);

    expect($result)->not->toBeNull();
    assertDatabaseHas($result->getTable(), ['id' => $result->id, 'parent_id' => $root->id, 'name' => 'foo']);
});

test('update driven model', function () {
    $driven = AppliesToDrivenEntityEntity::new('foo');
    $action = fn (AppliesToDrivenEntityEntity $entity) => $entity->name = 'bar';

    $result = AppliesToDrivenEntityAction::executeUpdate($driven, $action);

    expect($result->name)->toBe('bar');
    assertDatabaseHas($result->getTable(), ['id' => $result->id, 'name' => 'bar']);
});

test('update not saves unchanged model', function () {
    $driven = AppliesToDrivenEntityEntity::new('foo');

    $action = fn (AppliesToDrivenEntityEntity $entity) => $entity->name = 'foo';
    travel(1)->minutes();

    AppliesToDrivenEntityAction::executeUpdate($driven, $action);

    assertDatabaseHas($driven->getTable(), ['id' => $driven->id, 'updated_at' => $driven->updated_at]);
});

test('update prevents changing parent', function () {
    $driven = AppliesToDrivenEntityEntity::new('foo');
    $action = fn (AppliesToDrivenEntityEntity $entity) => $entity->parent_id = $entity->parent_id + 1;

    AppliesToDrivenEntityAction::executeUpdate($driven, $action);

    assertDatabaseHas($driven->getTable(), ['id' => $driven->id, 'parent_id' => $driven->parent_id]);
});

test('delete driven model', function () {
    $driven = AppliesToDrivenEntityEntity::new('foo');

    AppliesToDrivenEntityAction::executeDelete($driven);

    assertModelMissing($driven);
});

test('delete calls action before', function () {
    $driven = AppliesToDrivenEntityEntity::new('foo');
    $action = fn () => throw new RuntimeException('Action called');

    expect(fn () => AppliesToDrivenEntityAction::executeDelete($driven, $action))
        ->toThrow(RuntimeException::class, 'Action called');

    assertDatabaseHas($driven->getTable(), ['id' => $driven->id]);
});

test('apply saves nothing', function () {
    $driven = AppliesToDrivenEntityEntity::new('foo');

    $action = function (AppliesToDrivenEntityEntity $entity) {
        $entity->name = 'bar';

        return $entity;
    };

    AppliesToDrivenEntityAction::executeApply($driven, $action);

    assertDatabaseHas($driven->getTable(), ['id' => $driven->id, 'name' => 'foo']);
});
