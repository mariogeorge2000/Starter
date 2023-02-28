<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
           'name_ar'=>'required|max:100|unique:offer,name_ar',
            'name_en'=>'required|max:100|unique:offer,name_en',
            'price'=>'required|numeric',
            'details_ar'=>'required',
            'details_en'=>'required',

        ];
    }

    public function messages()
    {
      return [
             'name_ar.required' =>__('messages.offer name required'), //messages da esm el file
             'name_en.unique' => __('messages.offer name must be unique'),
            // w nkml el ba2i 3arabi w english
             ];
    }

    public function validateResolved()
    {
        // TODO: Implement validateResolved() method.
    }
}
