<?php

namespace App\Domain\Support\Concerns;

interface SetsUserAttributesInterface
{
    public function setCreatedBy(?string $value): void;
}
