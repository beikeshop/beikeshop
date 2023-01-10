<?php

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email'    => 'required|email:rfc|unique:customers,email',
            'password' => 'required|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'email'    => trans('shop/login.email_address'),
            'password' => trans('shop/login.password'),
        ];
    }
}
