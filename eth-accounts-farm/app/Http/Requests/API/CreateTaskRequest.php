<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'key'                  => 'required|string',
            'tasks.*'              => 'required_array_keys:tx_to,tx_value,tx_data',
            'tasks.*.tx_to'        => 'string',
            'tasks.*.tx_value'     => 'string',
            'tasks.*.tx_data'      => 'string',
            'tasks.*.priority'     => 'integer',
            'tasks.*.post_at'      => 'date',
            'tasks.*.post_at_node' => 'string|nullable',
            'tasks.*.role_id'      => 'integer|in:0,1',
        ];
    }
}
