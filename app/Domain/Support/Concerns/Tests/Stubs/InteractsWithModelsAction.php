<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Concerns\InteractsWithModels;
use App\Domain\Support\Models\Model;
use App\Exceptions\ConstraintViolationException;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class InteractsWithModelsAction
{
    use InteractsWithModels;

    public ?ConstraintViolationException $constraintException;

    public static function new(): self
    {
        return new self();
    }

    public function save(Model $model): void
    {
        $this->saveOrThrow($model);
    }

    public function delete(Model $model): void
    {
        $this->deleteOrThrow($model);
    }

    public function saveRelated(HasOneOrMany $relation, Model $model): void
    {
        $this->saveRelatedOrThrow($relation, $model);
    }

    protected function handleConstraintViolation(ConstraintViolationException $exception): void
    {
        $this->constraintException = $exception;
    }
}
