<?php

namespace App\Domain\Nomenclatures\Models;

use App\Domain\Documents\Models\DocumentNomenclature;
use App\Domain\Nomenclatures\Models\Factories\NomenclatureFactory;
use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class Nomenclature extends Model
{
    use SoftDeletes;

    protected $fillable = self::FILLLABLE;

    protected $attributes = [
        'is_new' => false
    ];

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
        'base_price',
        'is_new',
        'cardonor_id',
        'zippy_nomenclature_id'
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

    public function documents()
    {
        return $this->hasMany(DocumentNomenclature::class);
    }

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