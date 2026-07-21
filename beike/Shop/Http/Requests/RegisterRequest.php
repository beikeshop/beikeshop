<?php

namespace Beike\Shop\Http\Requests;

use Beike\Models\Customer;
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
            'email'    => [
                'required',
                'email:rfc',
                function ($attribute, $value, $fail) {
                    // 检查 email 是否已被注册（未被软删除）
                    if (Customer::where('email', $value)->withoutTrashed()->exists()) {
                        $fail(trans('shop/login.email_address_error', ['email' => $this->attributes()['email']]));
                    }
                    // 检查 email 是否在回收站中（已被软删除）
                    elseif (Customer::withTrashed()->where('email', $value)->onlyTrashed()->exists()) {
                        $fail(trans('shop/login.email_address_deleted'));
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
