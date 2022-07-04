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
            'email' => 'required|email:rfc,dns|unique:customers,email',
            'password' => 'required|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'email' => '邮箱地址',
            'password' => '密码'
        ];
    }
}
