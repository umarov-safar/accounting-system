<?php

namespace App\Domain\Services\Models;

use App\Domain\Services\Models\Factories\ServiceGroupFactory;
use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property int $seller_id
 * @property string $description
 * @property null|integer $parent_id
 */

class ServiceGroup extends Model
{
    use SoftDeletes;

    protected $fillable = self::FILLABLE;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    const FILLABLE = [
        'seller_id',
        'name',
        'description',
        'parent_id',
        'sort',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return ServiceGroupFactory::new();
    }

    public function canDelete(): self
    {
        // Тут пишите условие если условия правилная то можно удалить модель
        if ( true ) {
            return $this;
        }
        throw new AccessDeniedException('Нельзя удалить связный модель');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    getXXXAttribute()
    читатели
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    setXXXAttribute($value)
    преобразователи
    */
}