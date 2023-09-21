<?php

namespace App\Domain\Documents\Models;
use App\Domain\Documents\Models\Scopes\DocumentTypeScope;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Domain\Documents\Models\Factories\ReceiptDocumentFactory;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ReceiptDocument extends Document
{
    protected $table = 'documents';

    protected $fillable = self::FILLABLE;

    const FILLABLE = [
        'seller_id',
        'document_type_id',
        'status',
        'document_date',
        'company_id',
        'store_id',
        'summa',
        'discount',
        'overheads',
        'note',
        'company_to_id',
        'parent_id'
    ];

    protected $casts = [
        'document_type_id' => DocumentStoreTypeIdEnum::class,
        'status' => DocumentStatusEnum::class
    ];


    protected $attributes = [
        'document_type_id' => DocumentStoreTypeIdEnum::RECEIPT,
        'summa' => 'integer'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return ReceiptDocumentFactory::new();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function nomenclatureDocuments(): HasMany
    {
        return $this->hasMany(DocumentNomenclature::class)
            ->where('document_type_id', DocumentStoreTypeIdEnum::RECEIPT);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::addGlobalScope(new DocumentTypeScope(DocumentStoreTypeIdEnum::RECEIPT));
    }
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
