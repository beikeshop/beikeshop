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
            'name'       => 'required|min:2|max:16',
            'country_id' => 'required|exists:countries,id',
            'zone_id'    => 'required|exists:zones,id',
            'address_1'  => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'       => trans('address.name'),
            'country_id' => trans('address.country_id'),
            'zone_id'    => trans('address.zone_id'),
            'address_1'  => trans('address.address_1'),
        ];
    }
}
