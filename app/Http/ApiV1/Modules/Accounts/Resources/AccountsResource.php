<?php

namespace App\Http\ApiV1\Modules\Accounts\Resources;

use App\Domain\Accounts\Models\Account;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin Account
 */
class AccountsResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'seller_id' => $this->seller_id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'description' => $this->description,
        ];
    }
}