<?php

namespace App\Domain\Nomenclatures\Models;

use App\Domain\Nomenclatures\Models\Factories\NomenclatureFactory;
use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class Nomenclature extends Model
{
    use SoftDeletes;

    protected $fillable = self::FILLLABLE;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const FILLLABLE = [
        'seller_id',
        'is_service',
        'obj_type',
        'obj_id',
        'base_price'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return NomenclatureFactory::new();
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