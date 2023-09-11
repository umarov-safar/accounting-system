<?php

namespace App\Domain\Accounts\Actions;

use App\Domain\Accounts\Models\Account;
use Illuminate\Support\Arr;

class CreateAccountAction
{
    public function execute(array $fields)
    {
        return Account::create(Arr::only($fields, Account::FILLLABLE));
    }
}