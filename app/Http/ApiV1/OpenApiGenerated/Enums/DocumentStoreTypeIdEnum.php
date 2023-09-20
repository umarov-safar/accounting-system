<?php

/**
 * NOTE: This file is auto generated by OpenAPI Generator.
 * Do NOT edit it manually. Run `php artisan openapi:generate-server`.
 */

namespace App\Http\ApiV1\OpenApiGenerated\Enums;

/**
 * Тип документа:
 * * 101 - Покупка
 * * 102 - Продажа
 * * 103 - Оприходование
 * * 104 - Списание
 * * 105 - Возврат от покупателя
 * * 106 - Возврат от поставщика
 * * 107 - Перемещение
 */
enum DocumentStoreTypeIdEnum: int
{
    /** Покупка */
    case PURCHASE = 101;
    /** Продажа */
    case SALE = 102;
    /** Оприходование */
    case RECEIPT = 103;
    /** Списание */
    case WRITE_OFF = 104;
    /** Возврат от покупателя */
    case REFUND_FROM_CUSTOMER = 105;
    /** Возврат от поставщика */
    case REFUND_FROM_SUPPLIER = 106;
    /** Перемещение */
    case MOVING = 107;
}
