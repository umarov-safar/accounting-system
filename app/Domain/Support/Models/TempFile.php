<?php

namespace App\Domain\Support\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $path
 *
 * @method static Builder|static wherePath(string $path)
 */
class TempFile extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['path'];

    public function evict(): string
    {
        throw_unless(
            $this->delete(),
            "Не удалось захватить файл \"{$this->path}\""
        );

        return $this->path;
    }

    public static function grab(?int $id): ?string
    {
        return 0 === (int)$id
            ? null
            : self::findOrFail($id)->evict();
    }
}
