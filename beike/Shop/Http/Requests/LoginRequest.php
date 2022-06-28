<?php

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login.email' => 'required|email:rfc,dns',
            'login.password' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'login.email' => '邮箱地址',
            'login.password' => '密码'
        ];
    }
}
