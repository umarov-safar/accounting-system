<?php

namespace App\Domain\Documents\Models\Accesses;

use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @mixin  \App\Domain\Documents\Models\Document
 */
trait AccessibleDocumentMethods
{
    public function canBeDelete(): self
    {
        // Тут пишите условие если условия правильная то можно удалить модель
        if ( $this->status !== DocumentStatusEnum::FIX ) {
            return $this;
        }
        throw new AccessDeniedException('Нельзя удалить связный модель');
    }

    public function canBeEdit(): bool|static
    {
        return $this->status === DocumentStatusEnum::DRAFT ? $this : false;
    }

    public function canBeEditWithException(string $message = null): static
    {
        throw_unless(
            $this->canBeEdit(),
            new AccessDeniedException($message ?? 'Статус должен быть черновиком')
        );

        return $this;
    }

    /**
     * Все логика для установления статус на отмена документа здесь будет написано
     * @return $this|void
     */
    public function canBeSetStatusToCancel()
    {
        if ($this->status === DocumentStatusEnum::DRAFT) {
            return $this;
        }
    }

    public function canBeSetStatusToCancelWithException()
    {
        return throw_unless(
            $this->canBeSetStatusToCancel(),
            new AccessDeniedException($message ?? 'Статус должен быть черновиком')
        );
    }
}