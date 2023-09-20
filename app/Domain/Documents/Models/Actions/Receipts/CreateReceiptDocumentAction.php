<?php

namespace App\Domain\Documents\Models\Actions\Receipts;


use App\Domain\Documents\Models\ReceiptDocument;
use Illuminate\Support\Arr;

class CreateReceiptDocumentAction
{
    public function execute(array $fields): ReceiptDocument
    {
        return ReceiptDocument::create(Arr::only($fields, ReceiptDocument::FILLABLE));
    }
}