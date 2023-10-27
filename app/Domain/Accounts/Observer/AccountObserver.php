<?php

namespace App\Domain\Accounts\Observer;

use App\Domain\Accounts\Models\Account;
use Eijen\Zstore\Api\MoneyfundApi;
use Eijen\Zstore\Model\CreateMoneyfundRequest;

class AccountObserver
{

    public function __construct(
        protected MoneyfundApi $zMoneyfundApi
    )
    {
    }


    public function creating(Account $account)
    {
        $request = new CreateMoneyfundRequest([
            'mf_name' => $account->name,
            'description' => $account->description
        ]);
        $moneyFund = $this->zMoneyfundApi->createMoneyfund($request);
        $account->zippy_account_id = $moneyFund->getData()->getMfId();
    }


    public function updating(Account $account)
    {
        $request = new CreateMoneyfundRequest([
            'mf_name' => $account->name,
            'description' => $account->description
        ]);

        $this->zMoneyfundApi->patchMoneyfund($account->zippy_account_id ?? 0, $request);
    }

    public function deleted(Account $account)
    {
        $this->zMoneyfundApi->deleteMoneyfund($account->zippy_account_id);
    }

}