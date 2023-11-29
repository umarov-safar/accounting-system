<?php

namespace App\Providers;

use App\Domain\Accounts\Models\Account;
use App\Domain\Accounts\Observer\AccountObserver;
use App\Domain\Documents\Models\ReceiptDocument;
use App\Domain\Documents\Observers\ReceiptDocumentObserver;
use App\Domain\Services\Models\Service;
use App\Domain\Services\Observer\ServiceObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    public function boot(): void
    {
        //
        ReceiptDocument::observe(ReceiptDocumentObserver::class);
        Account::observe(AccountObserver::class);
        Service::observe(ServiceObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
