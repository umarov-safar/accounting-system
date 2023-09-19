<?php

namespace App\Domain\Documents\Models;


use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentFinanceTypeIdEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;


class DocumentType
{
    public function __construct(
        public readonly DocumentStoreTypeIdEnum|DocumentFinanceTypeIdEnum $id,
        public readonly string $title,
        public readonly int $target
    )
    {
    }


    public static function all(): array
    {
        return [
            'store' => [
                'section' => 'Типы складских документов',
                'items' => self::store()
            ],
            'finance' => [
                'section' => 'Типы финансовых документов',
                'items' => self::finance()
            ]
        ];
    }


    public static function findInStore(int $id): DocumentStoreTypeIdEnum|null
    {
        return DocumentStoreTypeIdEnum::tryFrom($id);
    }


    public static function store(): array
    {
        return [
            new static(
                DocumentStoreTypeIdEnum::PURCHASE,
                'Покупка',
                1
            ),
            new static(
                DocumentStoreTypeIdEnum::SALE,
                'Продажа',
                -1
            ),
            new static(
                DocumentStoreTypeIdEnum::RECEIPT,
                'Оприходование',
                1
            ),
            new static(
                DocumentStoreTypeIdEnum::WRITE_OFF,
                'Списание',
                -1
            ),
            new static(
                DocumentStoreTypeIdEnum::REFUND_FROM_CUSTOMER,
                'Возврат от покупателя',
                1
            ),
            new static(
                DocumentStoreTypeIdEnum::REFUND_FROM_SUPPLIER,
                'Возврат от поставщика',
                -1
            ),
            new static(
                DocumentStoreTypeIdEnum::MOVING,
                'Перемещение',
                -1
            )
        ];
    }

    public static function finance(): array
    {
        return [
            new static(
                DocumentFinanceTypeIdEnum::CUSTOMER_PAYMENT,
                'Оплата покупателя',
                1
            ),
            new static(
                DocumentFinanceTypeIdEnum::SUPPLIER_PAYMENT,
                'Оплата поставщику',
                -1
            ),
            new static(
                DocumentFinanceTypeIdEnum::DEPOSIT,
                'Внесение средства',
                1
            ),
            new static(
                DocumentFinanceTypeIdEnum::DEPOSIT,
                'Изъятие средства',
                -1
            ),
            new static(
                DocumentFinanceTypeIdEnum::REFUND_CUSTOMER,
                'Возврат покупателю',
                -1
            ),
            new static(
                DocumentFinanceTypeIdEnum::REFUND_SUPPLIER,
                'Возврат поставщика',
                1
            ),
            new static(
                DocumentFinanceTypeIdEnum::MOVING,
                'Перемещение',
                -1
            ),
        ];
    }

}