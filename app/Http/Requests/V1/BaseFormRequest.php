<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFromRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
