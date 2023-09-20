<?php

namespace App\Domain\Documents\Models\Factories;

use Illuminate\Database\Eloquent\Model;

class ReceiptDocumentFactory extends BaseDocumentFactory
{
    public function definition()
    {
        return \Arr::except(parent::definition(), ['contractor_id', 'store_to_id', 'payment_end_date']);
    }
}