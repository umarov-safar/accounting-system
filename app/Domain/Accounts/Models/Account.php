<?php

namespace App\Domain\Accounts\Models;

use App\Domain\Accounts\Models\Factories\AccountFactory;
use App\Domain\Support\Models\Model;
use App\Http\ApiV1\OpenApiGenerated\Enums\AccountTypeEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @property int $seller_id
 * @property string $name
 * @property AccountTypeEnum $type
 * @property boolean $is_active
 * @property string $description
 * @property $zippy_account_id
 */
class Account extends Model
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
        'name',
        'is_active',
        'type',
        'description',
        'zippy_account_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return AccountFactory::new();
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