<?php

namespace App\Domain\Support\Models;

use Carbon\CarbonInterface;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Базовый класс для моделей сервиса.
 *
 * @property int $id
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 *
 * @method static static|EloquentCollection|null find(int|int[] $id, array $columns = [])
 * @method static static|EloquentCollection|static[] findOrFail(int|int[] $id, array $columns = [])
 * @method static static create(array $fields)
 * @method static static firstOrCreate(array $attributes = [], array $values = [])
 *
 * @method static static|Builder lockForUpdate()
 * @method static static|Builder where(string $column, $operator = null, $value = null, string $boolean = 'and')
 * @method static static|Builder whereKey($id)
 */
class Model extends EloquentModel
{
    /**
     * Возвращает представление модели для логирования.
     * @return string
     */
    public function __toString(): string
    {
        $class = class_basename($this);

        return $this->exists
            ? "{$class} [{$this->getKey()}]"
            : "{$class}";
    }

    public static function tableName(): string
    {
        return (new static())->getTable();
    }

    /**
     * Регистрирует обработчики после действительной записи модели в БД.
     * Saved не означает, что модель в БД обновлялась.
     *
     * @param Closure|string $callback
     */
    public static function updatedOrCreated($callback): void
    {
        static::created($callback);
        static::updated($callback);
    }
}
