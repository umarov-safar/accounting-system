<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InteractsWithModelsRoot extends Model
{
    public const TABLE_NAME = 'test_interacts_with_models_root';

    protected $table = self::TABLE_NAME;
    public $timestamps = false;
    protected static $unguarded = true;

    public $eventResult = true;

    public function related(): HasOne
    {
        return $this->hasOne(InteractsWithModelsRelated::class, 'root_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (InteractsWithModelsRoot $model) {
            return $model->eventResult;
        });

        static::deleting(function (InteractsWithModelsRoot $model) {
            return $model->eventResult;
        });
    }
}
