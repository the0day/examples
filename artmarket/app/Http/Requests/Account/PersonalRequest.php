<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class PersonalRequest extends FormRequest
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
            'firstname'  => 'required',
            'lastname'   => 'required',
            'email'      => 'required|email|unique:users,email,' . $this->user()->id,
            'phone'      => 'required|numeric',
            'country_id' => 'integer|min:0|exists:countries,id',
            'city_id'    => 'integer|min:0|exists:cities,id,country_id,' . request()->get('country_id'),
        ];
    }
}
