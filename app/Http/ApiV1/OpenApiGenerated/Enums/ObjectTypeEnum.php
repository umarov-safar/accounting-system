<?php

/**
 * NOTE: This file is auto generated by OpenAPI Generator.
 * Do NOT edit it manually. Run `php artisan openapi:generate-server`.
 */

namespace App\Http\ApiV1\OpenApiGenerated\Enums;

/**
 * Тип объекта:
 * * product - Товар
 * * service - Услуг
 */
enum ObjectTypeEnum: string
{
    /** Товар */
    case PRODUCT = 'product';
    /** Услуг */
    case SERVICE = 'service';
}
