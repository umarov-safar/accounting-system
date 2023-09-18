<?php

namespace App\Domain\Documents\Models;

use App\Domain\Documents\Models\Factories\DocumentFactory;
use App\Domain\Support\Models\Model;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @property $id
 * @property $seller_id
 * @property $document_type_id
 * @property $status // Enum 1-draft, 2-fix, 3-cancel
 * @property $document_date
 * @property $company_id
 * @property $contractor_id
 * @property $store_id // откуда
 * @property $store_to_id // куда
 * @property $summa
 * @property $discount
 * @property $overheads
 * @property $note
 */
class Document extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const FILLLABLE = [
        'seller_id',
        'document_type_id',
        'status',
        'document_date',
        'company_id',
        'contractor_id',
        'store_id',
        'store_to_id',
        'summa',
        'discount',
        'overheads',
        'note',
        'payment_end_date',
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return DocumentFactory::new();
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

    public function nomenclatures()
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