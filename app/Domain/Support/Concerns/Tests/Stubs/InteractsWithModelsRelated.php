<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Models\Model;

class InteractsWithModelsRelated extends Model
{
    public const TABLE_NAME = 'test_interacts_with_models_related';

    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected static $unguarded = true;

    public $eventResult = true;

    protected static function boot()
    {
        parent::boot();

        static::saving(function (InteractsWithModelsRelated $model) {
            return $model->eventResult;
        });

        static::deleting(function (InteractsWithModelsRelated $model) {
            return $model->eventResult;
        });
    }
}
