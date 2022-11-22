<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class WorkerRegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'chain_id'        => 'required|integer',
            'label'           => 'required|string',
            'task_callback'   => 'required|url',
            'status_callback' => 'required|url',
            //'admin_key'       => 'required|string',
            //'key'             => 'required|string',
            'role'            => 'required|integer|in:0,1',
            'pk'              => 'array',
            'q'               => 'integer|min:0|max:100'
        ];
    }
}
