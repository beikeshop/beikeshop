<?php

namespace Beike\Shop\Http\Requests;

use Beike\Models\Customer;
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
            'email'    => ['required','email:rfc',
                function ($attribute, $value, $fail) {
                    if (Customer::where('email', $value)->where('id', '<>', current_customer()->id)->exists()) {
                        $fail(trans('shop/login.email_address_error', ['email' => $this->attributes()['email']]));
                    }
                },
            ],
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
