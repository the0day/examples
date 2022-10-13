<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'notes'          => 'string|max:1024',
            'deadline'       => 'required|date_format:d/m/Y|after:tomorrow|before:+30 days',
            'image.*'        => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'payment_method' => 'required|string|exists:payment_methods,name,active,1'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
