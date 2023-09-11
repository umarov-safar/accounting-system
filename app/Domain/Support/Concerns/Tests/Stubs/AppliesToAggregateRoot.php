<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Models\Model;

/**
 * @property string $name
 */
class AppliesToAggregateRoot extends Model
{
    public const TABLE_NAME = 'test_applies_to_aggregate_root';

    protected $table = self::TABLE_NAME;
    protected static $unguarded = true;
}
