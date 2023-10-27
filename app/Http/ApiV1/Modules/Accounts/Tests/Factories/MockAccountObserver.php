<?php

namespace App\Http\ApiV1\Modules\Accounts\Tests\Factories;

use App\Domain\Accounts\Observer\AccountObserver;

class MockAccountObserver extends AccountObserver
{
    public function creating($a) {echo 'creating';}
    public function updating($a) {}
    public function deleting($a) {}
}