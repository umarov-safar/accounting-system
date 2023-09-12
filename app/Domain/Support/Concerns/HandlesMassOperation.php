<?php

namespace App\Domain\Support\Concerns;

use App\Domain\Support\MassOperationResult;
use App\Domain\Support\Models\Model;
use App\Domain\Support\Models\Pivot;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HandlesMassOperation
{
    public function each(array $ids, Closure $handler): MassOperationResult
    {
        $result = new MassOperationResult();

        foreach ($ids as $entityId) {
            try {
                $handler($entityId);
                $result->success($entityId);
            } catch (ModelNotFoundException) {
                // Игнорируем
            } catch (Exception $exception) {
                $result->error($entityId, $exception);
            }
        }

        return $result;
    }

    /**
     * @param EloquentCollection $models
     * @param Closure $handler
     * @return MassOperationResult
     */
    public function eachModel(EloquentCollection $models, Closure $handler): MassOperationResult
    {
        $result = new MassOperationResult();

        /** @var Model|Pivot $model */
        foreach ($models as $model) {
            try {
                $handler($model);
                $result->success($model->id);
            } catch (ModelNotFoundException) {
                // Игнорируем
            } catch (Exception $exception) {
                $result->error($model->id, $exception);
            }
        }

        return $result;
    }
}
