<?php

namespace App\Domain\Services\Observer;

use App\Domain\Services\Models\Service;
use Eijen\Zstore\Api\ServicesApi;
use Eijen\Zstore\Model\CreateServiceRequest;

class ServiceObserver
{
    public function __construct(
        protected ServicesApi $zServiceApi
    )
    {
    }


    public function creating(Service $account)
    {
        $request = new CreateServiceRequest([
            'service_name' => $account->name,
            'price' => $account->base_price
        ]);
        $service = $this->zServiceApi->createService($request);
        $account->zippy_service_id = $service->getData()->getServiceId();
    }


    public function updating(Service $account)
    {
        $request = new CreateServiceRequest([
            'service_name' => $account->name,
            'price' => $account->base_price
        ]);

        $this->zServiceApi->patchService($account->zippy_service_id ?? 0, $request);
    }

    public function deleted(Service $account)
    {
        $this->zServiceApi->deleteService($account->zippy_service_id);
    }
}