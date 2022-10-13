<?php

namespace App\Http\Requests\Glossary;

use Illuminate\Foundation\Http\FormRequest;

class OptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'        => 'required|string',
            'alias'        => 'required',
            'group_id'     => 'required|string|exists:App\Models\Glossary\OptionGroup,id',
            'icon'         => 'string|nullable',
            'price'        => 'nullable|numeric',
            'field_type'   => 'required|integer|min:1|max:5',
            'field_values' => 'nullable|json',
            'measure_unit' => 'nullable|string',
            'order'        => 'integer|nullable',
            'active'       => 'boolean'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
