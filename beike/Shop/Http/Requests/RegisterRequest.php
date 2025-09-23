<?php

namespace Beike\Shop\Http\Requests;

use Beike\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email'    => [
                'required',
                'email:rfc',
                function ($attribute, $value, $fail) {
                    if (Customer::where('email', $value)->exists()) {
                        // 验证失败，返回错误信息
                        $fail(trans('shop/login.email_address_error', ['email' => $this->attributes()['email']]));
                    }
                },
            ],
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
