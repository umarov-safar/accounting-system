<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpParamsInspection */

namespace App\Domain\Support\Concerns;

use App\Domain\Support\Models\Model;
use App\Domain\Support\Models\Pivot;
use App\Exceptions\ConstraintViolationException;
use App\Exceptions\OperationRejectedException;
use Closure;
use Ensi\LaravelAuditing\Facades\Transaction;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * InteractsWithModels определяет обертки для некоторых методов моделей.
 */
trait InteractsWithModels
{
    /** @noinspection PhpUnhandledExceptionInspection */
    protected function transaction(Closure $action)
    {
        return Transaction::isActive()
            ? $action()
            : DB::transaction($action);
    }

    protected function saveOrThrow(Model|Pivot $model): void
    {
        $this->executeModelOperation($model, 'save');
    }

    protected function saveRelatedOrThrow(HasOneOrMany $relation, Model|Pivot $model): void
    {
        $this->executeModelOperation($model, 'save', $relation);
    }

    protected function deleteOrThrow(Model|Pivot $model): void
    {
        $this->executeModelOperation($model, 'delete');
    }

    protected function forceDeleteOrThrow(Model|Pivot $model): void
    {
        $this->executeModelOperation($model, 'forceDelete');
    }

    protected function executeModelOperation(Model|Pivot $model, string $operation, ?object $executor = null): void
    {
        try {
            $result = $executor !== null
                ? $executor->{$operation}($model) !== false
                : $model->{$operation}();

            /** @psalm-suppress InvalidArgument */
            throw_unless(
                $result,
                OperationRejectedException::class,
                "Операция \"{$operation}\" для модели {$model} была отклонена"
            );
        } catch (QueryException $exception) {
            $this->handleQueryException($exception, $model);
        }
    }

    protected function handleQueryException(QueryException $exception, Model|Pivot $model): void
    {
        if (in_array($exception->getCode(), ConstraintViolationException::codes())) {
            $this->handleConstraintViolation(
                new ConstraintViolationException($model, $exception->getMessage(), $exception->getCode(), $exception)
            );

            return;
        }

        throw $exception;
    }

    protected function handleConstraintViolation(ConstraintViolationException $exception): void
    {
        throw $exception;
    }
}
