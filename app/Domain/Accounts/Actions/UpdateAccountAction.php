<?php

namespace App\Domain\Accounts\Actions;

use App\Domain\Accounts\Models\Account;

class UpdateAccountAction
{
    public function execute(int $id, array $fields)
    {
        $account = Account::findOrFail($id);
        $account->update($fields);
        return $account;
    }
}