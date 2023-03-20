<?php

namespace App\Http\Requests;

use App\Traits\ResponseWithHttpRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
 use ResponseWithHttpRequest;

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
            'product_id'                => 'required|exists:products,id',            
            'product_quantity'          => 'required|integer',
            'device_token'              => 'required',
            'unique_id'                 => 'required',
            'user_id'                   => 'nullable|exists:users,id',
            'address'                   => 'required',
            'address_type'              => 'required|In:mosque,home',
            'language_type'            => 'required|In:en,ar-qa',
        ];
    }

    public function messags()
    {
        return [
            'product_id.required'      => 'Product Id should be required',
            'product_id.exists'        => 'Product Id not found',            
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->sendFailed($validator->errors()->first(), 200)
        //     response()->json([
        //     'ResponseCode'      => 200,
        //     'Status'            => False,
        //     'Message'           => $validator->errors()->first()
        // ])
    
    );
    }
}