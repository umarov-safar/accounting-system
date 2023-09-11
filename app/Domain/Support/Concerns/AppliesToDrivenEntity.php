<?php

namespace App\Domain\Support\Concerns;

use App\Domain\Support\Models\Model;
use Closure;
use Ensi\LaravelAuditing\Facades\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Webmozart\Assert\Assert;

trait AppliesToDrivenEntity
{
    use InteractsWithModels;

    /**
     * Создает новую модель корневой сущности.
     */
    abstract protected function createRootModel(): Model;

    /**
     * Создает новую модель управляемой сущности.
     */
    abstract protected function createModel(): Model;

    /**
     * Возвращает имя связи HasOne или HasMany, определенной в корневой сущности.
     */
    abstract protected function getRelationNameInRoot(): string;

    protected function apply(int $entityId, Closure $action): mixed
    {
        return $this->transactionWithRoot(
            $entityId,
            null,
            fn (Model $root) => $action($this->findOrFail($entityId), $root)
        );
    }

    protected function updateOrCreate(?int $entityId, ?int $rootId, Closure $action): mixed
    {
        return $this->transactionWithRoot($entityId, $rootId, function (Model $root) use ($entityId, $action) {
            $model = $entityId === null
                ? $this->createModel()
                : $this->findOrFail($entityId);

            $action($model, $root);

            $relationName = $this->getRelationNameInRoot();
            $this->saveRelatedOrThrow($root->{$relationName}(), $model);

            return $model;
        });
    }

    protected function delete(int $entityId, ?Closure $action = null): void
    {
        $this->apply($entityId, function (Model $entity, Model $root) use ($action) {
            if ($action !== null) {
                $action($entity, $root);
            }

            $this->deleteOrThrow($entity);
        });
    }

    protected function findOrFail(int $entityId): Model
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createModel()
            ->newQuery()
            ->findOrFail($entityId);
    }

    protected function transactionWithRoot(?int $entityId, ?int $rootId, Closure $action)
    {
        Assert::notNull($rootId ?? $entityId);

        return $this->transaction(function () use ($entityId, $rootId, $action) {
            $rootQuery = $this->createRootModel()
                ->newQuery()
                ->lockForUpdate();

            $root = $rootId !== null
                ? $rootQuery->findOrFail($rootId)
                : $rootQuery->whereHas($this->getRelationNameInRoot(), fn (Builder $query) => $query->whereKey($entityId))
                    ->firstOrFail();

            Transaction::setRootEntity($root);

            return $action($root);
        });
    }
}
