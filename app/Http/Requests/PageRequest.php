<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'page_title'         => 'required|max:255',
            // 'page_slug'          => 'required|unique:pages,page_slug',
            'page_details'       => 'required',
        ];
    }
    public function messages()
    {
        return [
            'page_title.required'          => 'Title should be required',
            'page_title.max'               => 'Title  max length 255',
            // 'page_slug.required'           => 'Slug  should be required',
            // 'page_slug.unique'                => 'Slug  should be Unique',
            'page_details.required'        => 'Page Details should be required',
        ];
    }
}
