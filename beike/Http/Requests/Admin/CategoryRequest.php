<?php

namespace Beike\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'descriptions.*.name' => 'required|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'descriptions.*.name' => '名称',
        ];
    }
}
