<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'offer_id' => 'integer|exists:offers,id,active,1',
            'option'   => 'array',
            'option.*' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
