<?php

namespace App\Http\Requests\Account;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $offerTypeId = $this->request->get('offer_type_id');
        $id = $this->request->get('id');

        return [
            'id'                       => 'nullable|integer|exists:offers,id,user_id,' . Auth::user()->id,
            'offer_type_id'            => 'required|integer|exists:glossary_offer_types,id',
            'title'                    => 'string|required|min:10|max:80',
            'alias'                    => 'string',
            'description'              => 'string|max:1200',
            'price'                    => 'required|min:1',
            'category_ids'             => 'array',
            'category_ids.*'           => 'integer|exists:glossary_categories,id,offer_type_id,' . $offerTypeId,
            'options'                  => 'array',
            'options.*.name'           => 'nullable|string',
            'options.*.fields'         => 'array',
            'options.*.fields.*'       => 'array',
            'options.*.fields.*.name'  => 'string',
            'options.*.fields.*.days'  => 'nullable|integer',
            'options.*.fields.*.price' => 'nullable|numeric',
            'image'                    => 'required_without:id',
            'image.*'                  => 'required|file|mimes:png,jpg,jpeg|max:4096'
        ];
    }
}
