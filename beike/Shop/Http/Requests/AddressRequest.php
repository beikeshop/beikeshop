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
        $rules = [
            'name'       => 'required|min:2|max:64',
            'country_id' => 'required|exists:countries,id',
            'zone_id'    => 'required|exists:zones,id',
            'address_1'  => 'required',
        ];

        if (system_setting('base.address_phoner_equired', '0') == '1') {
            $rules['phone'] = 'required';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name'       => trans('address.name'),
            'country_id' => trans('address.country_id'),
            'zone_id'    => trans('address.zone_id'),
            'address_1'  => trans('address.address_1'),
            'phone'      => trans('shop/account/addresses.enter_phone'),
        ];
    }
}
