<?php

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'name' => 'required|max:64',
            'code' => 'required|max:16',
            'symbol_left' => 'max:16',
            'symbol_right' => 'max:16',
            'value' => 'required',
            'decimal_place' => 'max:9',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'code' => '编码',
            'symbol_left' => '左符号',
            'symbol_right' => '右符号',
            'value' => '汇率值',
            'decimal_place' => '小数位数',
        ];
    }
}
