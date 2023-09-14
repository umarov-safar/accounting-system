<?php

namespace App\Domain\Services\Models;

use App\Domain\Services\Models\Factories\ServiceFactory;
use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @property string $name
 * @property int $seller_id
 * @property int $service_group_id
 * @property null|string $description
 * @property int $base_price
 */
class Service extends Model
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
        'service_group_id',
        'base_price',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function factory()
    {
        return ServiceFactory::new();
    }

    public function canDelete(): self|AccessDeniedException
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

    public function children()
    {
        return $this->hasMany(ServiceGroup::class, 'parent_id');
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