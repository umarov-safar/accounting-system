<?php

namespace App\Domain\Documents\Observers;

use App\Domain\Documents\Models\ReceiptDocument;

class ReceiptDocumentObserver
{
    public function created(ReceiptDocument $document)
    {
        // logic after creating in db
    }

    public function updated(ReceiptDocument $document)
    {
        // logic after updating in db
    }

    public function creating(ReceiptDocument $document)
    {
        // logic before model created in db
    }

    public function updating(ReceiptDocument $document)
    {
        // logic before updating in db
    }


}