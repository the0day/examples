<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUploadWork extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sketches'   => 'array',
            'sketches.*' => 'file|mimes:png,jpg,jpeg|max:4096',
            'final'      => 'file|mimes:png,jpg,jpeg|max:4096'
        ];
    }
}
