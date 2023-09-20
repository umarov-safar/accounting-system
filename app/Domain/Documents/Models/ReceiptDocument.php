<?php

namespace App\Domain\Documents\Models;
use Illuminate\Database\Eloquent\Builder;
use App\Domain\Documents\Models\Factories\ReceiptDocumentFactory;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
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


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return ReceiptDocumentFactory::new();
    }

    public function canDelete(): self
    {
        // Тут пишите условие если условия правильная то можно удалить модель
        if ( true ) {
            return $this;
        }
        throw new AccessDeniedException('Нельзя удалить связный модель');
    }


    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeDocumentTypeReceipt(Builder $query)
    {
        $query->where('document_type_id', DocumentStoreTypeIdEnum::RECEIPT->value);
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
