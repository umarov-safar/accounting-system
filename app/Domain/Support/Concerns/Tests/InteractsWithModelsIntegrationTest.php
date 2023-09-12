<?php

use App\Domain\Support\Concerns\Tests\Stubs\InteractsWithModelsAction;
use App\Domain\Support\Concerns\Tests\Stubs\InteractsWithModelsRelated;
use App\Domain\Support\Concerns\Tests\Stubs\InteractsWithModelsRoot;
use App\Exceptions\OperationRejectedException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;

use Tests\IntegrationTestCase;

uses(IntegrationTestCase::class)->group('support', 'integration');

beforeEach(function () {
    Schema::create(InteractsWithModelsRoot::TABLE_NAME, function (Blueprint $table) {
        $table->temporary();
        $table->id();
        $table->string('name');

        $table->unique('name');
    });

    Schema::create(InteractsWithModelsRelated::TABLE_NAME, function (Blueprint $table) {
        $table->temporary();
        $table->id();
        $table->string('name');
        $table->foreignId('root_id')->nullable();
    });
});

test('save', function () {
    $root = new InteractsWithModelsRoot(['name' => 'Test']);

    InteractsWithModelsAction::new()->save($root);

    expect($root->exists)->toBeTrue();
    assertDatabaseHas($root->getTable(), ['id' => $root->id]);
});

test('save failed', function () {
    $root = new InteractsWithModelsRoot(['name' => 'Test']);
    $root->eventResult = false;

    expect(fn () => InteractsWithModelsAction::new()->save($root))
        ->toThrow(OperationRejectedException::class);

    expect($root->exists)->toBeFalse();
});

test('delete', function () {
    $root = InteractsWithModelsRoot::create(['name' => 'Test']);

    InteractsWithModelsAction::new()->delete($root);

    assertModelMissing($root);
});

test('delete failed', function () {
    $root = InteractsWithModelsRoot::create(['name' => 'Test']);
    $root->eventResult = false;

    expect(fn () => InteractsWithModelsAction::new()->delete($root))
        ->toThrow(OperationRejectedException::class);

    assertDatabaseHas($root->getTable(), ['id' => $root->id]);
});

test('save related model', function () {
    $root = InteractsWithModelsRoot::create(['name' => 'Test']);
    $related = new InteractsWithModelsRelated(['name' => 'One']);

    InteractsWithModelsAction::new()->saveRelated($root->related(), $related);

    expect($related->exists)->toBeTrue();
    assertDatabaseHas($related->getTable(), ['id' => $related->id]);
});

test('save related failed', function () {
    $root = InteractsWithModelsRoot::create(['name' => 'Test']);
    $related = new InteractsWithModelsRelated(['name' => 'One']);
    $related->eventResult = false;

    expect(fn () => InteractsWithModelsAction::new()->saveRelated($root->related(), $related))
        ->toThrow(OperationRejectedException::class);

    expect($related->exists)->toBeFalse();
});

test('constraint unique', function () {
    InteractsWithModelsRoot::create(['name' => 'Test']);
    $root = new InteractsWithModelsRoot(['name' => 'Test']);

    $testing = InteractsWithModelsAction::new();
    $testing->save($root);

    expect($testing->constraintException)->not->toBeNull();
    expect($testing->constraintException->isUniqueViolation())->toBeTrue();
});

test('constraint not null', function () {
    $root = new InteractsWithModelsRoot();

    $testing = InteractsWithModelsAction::new();
    $testing->save($root);

    expect($testing->constraintException)->not->toBeNull();
    expect($testing->constraintException->isNotNullViolation())->toBeTrue();
});
