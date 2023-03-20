<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreMerchantRequest extends FormRequest
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
            // 'id'        => 'sometimes',
            'name'      => 'required',
            'email'     => 'required|email|max:255|unique:users,email,'.$this->id.',id',
            'mobile'    => 'required|min:6|max:13|unique:users,mobile,'.$this->id.',id',
            'password'  => 'required_without:id|max:20'            
        ];
    }

    public function failedValidation(Validator $validator)
{
   throw new HttpResponseException(response()->json(['success' => false, 'errors' => $validator->getMessageBag(), 'message' => ''], 422));
}

}
