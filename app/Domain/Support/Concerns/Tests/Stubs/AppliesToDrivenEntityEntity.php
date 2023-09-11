<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Models\Model;

/**
 * @property string $name
 * @property int $parent_id
 */
class AppliesToDrivenEntityEntity extends Model
{
    public const TABLE_NAME = 'test_applies_to_driven_entity';

    protected $table = self::TABLE_NAME;
    protected static $unguarded = true;

    protected $casts = ['parent_id' => 'int'];

    public static function new(string $name, ?AppliesToDrivenEntityRoot $root = null): AppliesToDrivenEntityEntity
    {
        $root ??= AppliesToDrivenEntityRoot::create([]);

        return static::create(['name' => $name, 'parent_id' => $root->id]);
    }
}
