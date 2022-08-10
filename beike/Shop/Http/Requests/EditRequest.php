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
            'name' => 'required',
            // 'email' => 'required|email:rfc,dns|unique:customers,email',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '姓名',
            'email' => '邮箱',
        ];
    }
}
