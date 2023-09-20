<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Queries;
use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\Modules\Documents\Queries\BaseDocumentQueries;

class ReceiptDocumentQueries extends BaseDocumentQueries
{
    public function __construct()
    {
        parent::__construct(resolve(ReceiptDocument::class));

        /**
         * @see ReceiptDocument::scopeDocumentTypeReceipt()
         */
        $this->documentTypeReceipt();
    }
}
