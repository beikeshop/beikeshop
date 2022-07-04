<?php

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'phone' => 'required',
            'country_id' => 'required|exists:countries,id',
            'zone_id' => 'required|exists:zones,id',
            'address_1' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '姓名',
            'phone' => '电话号码',
            'country_id' => '国家ID',
            'zone_id' => '省份ID',
            'address_1' => '地址1',
        ];
    }
}
