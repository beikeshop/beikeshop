<?php

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
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
            'name'  => 'required|string|min:2|max:16',
            'email' => 'required|email:rfc|unique:customers,email,' . current_customer()->id,
        ];
    }

    public function attributes()
    {
        return [
            'name'  => trans('shop/account/edit.name'),
            'email' => trans('shop/account/edit.email'),
        ];
    }
}
