<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [            
            'name'              => 'required',
            'name_ar_qa'        => 'required',
            'description'       => 'required',
            'description_ar_qa' => 'required',
            'price'             => 'numeric',
            'merchant_price'    => 'numeric',
            'image'             => 'required_without:id||image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ];
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json(['success' => false, 'errors' => $validator->getMessageBag(), 'message' => ''], 422));
    }
}
