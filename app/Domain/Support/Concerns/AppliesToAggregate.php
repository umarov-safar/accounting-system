<?php

namespace App\Domain\Support\Concerns;

use App\Domain\Support\Models\Model;
use App\Domain\Support\Models\Pivot;
use Closure;
use Ensi\LaravelAuditing\Facades\Transaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @template T
 */
trait AppliesToAggregate
{
    use InteractsWithModels;

    /**
     * Создает новую модель корневой сущности.
     *
     * @return Model
     */
    abstract protected function createModel(): Model;

    protected function findOrFail(int $entityId): Model
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createModel()
            ->newQuery()
            ->lockForUpdate()
            ->findOrFail($entityId);
    }

    /**
     * @param array $entityIds
     * @return EloquentCollection
     */
    protected function findMany(array $entityIds): EloquentCollection
    {
        return $this->createModel()
            ->newQuery()
            ->lockForUpdate()
            ->whereIn('id', $entityIds)
            ->get();
    }

    protected function setRootEntity(Model $model): void
    {
        Transaction::setRootEntity($model);
    }

    protected function apply(int $entityId, Closure $action): mixed
    {
        return $this->transaction(function () use ($entityId, $action) {
            $model = $this->findOrFail($entityId);

            $this->setRootEntity($model);

            return $action($model);
        });
    }

    protected function applyModel(Model|Pivot $model, Closure $action): void
    {
        $this->setRootEntity($model);
        $action($model);
    }

    /**
     * @return T
     */
    protected function updateOrCreate(?int $entityId, Closure $action): mixed
    {
        return $this->transaction(function () use ($entityId, $action) {
            $model = $entityId === null
                ? $this->createModel()
                : $this->findOrFail($entityId);

            $this->setRootEntity($model);

            $action($model);

            $this->saveOrThrow($model);

            return $model;
        });
    }

    protected function delete(int $entityId, ?Closure $action = null): void
    {
        $this->apply($entityId, function (Model $model) use ($action) {
            if ($action !== null) {
                $action($model);
            }

            $this->deleteOrThrow($model);
        });
    }

    /**
     * @return T
     */
    protected function replace(int $entityId, array $fields): mixed
    {
        return $this->updateOrCreate($entityId, function (Model $model) use ($fields) {
            $model->fill(
                data_combine_assoc($model->getFillable(), $fields)
            );
        });
    }

    /**
     * @return T
     */
    protected function patch(int $entityId, array $fields): mixed
    {
        return $this->updateOrCreate($entityId, function (Model $model) use ($fields) {
            $model->fill($fields);
        });
    }

    /**
     * @return T
     */
    protected function create(array $fields): mixed
    {
        return $this->updateOrCreate(null, function (Model $model) use ($fields) {
            $model->fill($fields);
        });
    }
}
