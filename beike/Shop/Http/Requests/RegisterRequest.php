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
            'register.email' => 'required|email:rfc,dns|unique:customers,email',
            'register.password' => 'required|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'register.email' => '邮箱地址',
            'register.password' => '密码'
        ];
    }
}
