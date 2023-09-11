<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Domain\Support\Actions;

use App\Domain\Support\Concerns\AppliesToAggregate;
use App\Domain\Support\Models\Model;
use App\Support\Files\FileIntent;
use App\Support\Files\FileStorage;
use App\Support\Files\LinksFile;
use Closure;
use Webmozart\Assert\Assert;

abstract class BaseFileAction
{
    use AppliesToAggregate;
    use LinksFile;

    private mixed $returnValue;

    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    protected function attach(int $entityId, FileIntent $intent, Closure|string $target): mixed
    {
        $wrapper = function (string $filePath) use ($entityId, $target) {
            return $this->setFileToModel($entityId, $target, $filePath);
        };

        $this->link($intent, $wrapper);

        return $this->popReturnValue();
    }

    protected function detach(int $entityId, Closure|string $target): mixed
    {
        $wrapper = function () use ($entityId, $target) {
            return $this->setFileToModel($entityId, $target);
        };

        $this->unlink($wrapper);

        return $this->popReturnValue();
    }

    protected function setFileToModel(int $entityId, $target, ?string $filePath = null): ?string
    {
        Assert::false(blank($target));

        $callback = function (Model $model) use ($target, $filePath) {
            $this->pushReturnValue($model);

            return ($target instanceof Closure)
                ? $target($model, $filePath)
                : $this->setFileToAttribute($model, $target, $filePath);
        };

        return $this->apply($entityId, $callback);
    }

    protected function setFileToAttribute(Model $model, string $attribute, ?string $filePath): ?string
    {
        $old = $model->{$attribute};
        $model->{$attribute} = $filePath;

        $this->saveOrThrow($model);

        return $old;
    }

    protected function pushReturnValue(Model $model): void
    {
        $this->returnValue = $model;
    }

    protected function popReturnValue(): mixed
    {
        $result = $this->returnValue;
        $this->returnValue = null;

        return $result;
    }
}
