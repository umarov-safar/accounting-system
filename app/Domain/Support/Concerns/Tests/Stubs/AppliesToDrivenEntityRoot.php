<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Collection|AppliesToDrivenEntityEntity[] $children
 */
class AppliesToDrivenEntityRoot extends Model
{
    public const TABLE_NAME = 'test_applies_to_driven_root';

    protected $table = self::TABLE_NAME;
    protected static $unguarded = true;

    public function children(): HasMany
    {
        return $this->hasMany(AppliesToDrivenEntityEntity::class, 'parent_id');
    }
}
