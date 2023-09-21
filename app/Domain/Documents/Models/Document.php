<?php

namespace App\Domain\Documents\Models;

use App\Domain\Documents\Models\Accesses\AccessibleDocumentMethods;
use App\Domain\Documents\Models\Factories\DocumentFactory;
use App\Domain\Support\Models\Model;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @property $id
 * @property $seller_id
 * @property $document_type_id
 * @property $status // Enum 1-draft, 2-fix, 3-cancel
 * @property $document_date
 * @property $company_id
 * @property $company_to_id
 * @property $contractor_id
 * @property $store_id // откуда
 * @property $store_to_id // куда
 * @property $summa
 * @property $discount
 * @property $overheads
 * @property $note
 */
abstract class Document extends Model
{
    use SoftDeletes, AccessibleDocumentMethods;

    protected $table = 'documents';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
//
//    protected $fillable = self::FILLABLE;
//
//    const FILLABLE = [
//        'seller_id',
//        'document_type_id',
//        'status',
//        'document_date',
//        'company_id',
//        'contractor_id',
//        'store_id',
//        'store_to_id',
//        'summa',
//        'discount',
//        'overheads',
//        'note',
//        'payment_end_date',
//        'company_to_id',
//        'parent_id'
//    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return DocumentFactory::new();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    abstract function nomenclatureDocuments(): HasMany;

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