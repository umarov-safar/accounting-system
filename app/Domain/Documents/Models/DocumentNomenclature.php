<?php

namespace App\Domain\Documents\Models;

use App\Domain\Documents\Models\Factories\DocumentNomenclatureFactory;
use App\Domain\Nomenclatures\Models\Nomenclature;
use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @property $document_id
 * @property $nomenclature_id
 * @property $quantity
 * @property $cost_price
 * @property $base_price
 * @property $discount
 * @property $overheads
 */
class DocumentNomenclature extends Model
{

   protected $fillable = self::FILLLABLE;

    const FILLLABLE = [
        'document_id',
        'nomenclature_id',
        'quantity',
        'cost_price',
        'base_price',
        'discount',
        'overheads',
        'quantity_fix',
        'quantity_no_fix',
        'parent_id',
    ];

    /*
       |--------------------------------------------------------------------------
       | FUNCTIONS
       |--------------------------------------------------------------------------
       */

    public static function factory()
    {
        return DocumentNomenclatureFactory::new();
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

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
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