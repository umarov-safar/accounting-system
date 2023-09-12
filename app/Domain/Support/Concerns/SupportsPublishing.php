<?php

namespace App\Domain\Support\Concerns;

use App\Domain\Support\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

/**
 * @mixin Model
 */
trait SupportsPublishing
{
    use SoftDeletes;

    abstract protected function newRelease(): Model;

    public function rollback(): void
    {
        if (!$this->exists) {
            return;
        }

        if ($this->getRelease() === null) {
            $this->forceDelete();

            return;
        }

        if ($this->trashed()) {
            $this->restore();
        }
    }

    public function commit(): void
    {
        if (!$this->exists) {
            return;
        }

        if ($this->trashed()) {
            $this->forceDelete();
            $release = $this->getRelease();
            $release?->delete();
        } else {
            $this->publish();
        }
    }

    public function publish(): void
    {
        $release = $this->getRelease() ?? $this->makeRelease();

        $release->forceFill(Arr::only($this->getAttributes(), $this->getFillable()))
            ->save();
    }

    protected function getRelease(): ?Model
    {
        return $this->getRelationValue($this->releaseRelationName());
    }

    protected function makeRelease(): Model
    {
        $release = $this->newRelease();
        $release->id = $this->id;

        $this->setRelation($this->releaseRelationName(), $release);

        return $release;
    }

    protected function releaseRelationName(): string
    {
        return 'release';
    }
}
