<?php
/**
 * AdminRoleRequest.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-12 17:48:08
 * @modified   2022-08-12 17:48:08
 */

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rule = [
            'name' => 'required|string|max:10',
            'rate' => 'required|numeric',
            'type' => 'required|in:percent,flat',
            'region_id' => 'required|int',
        ];

        if ($this->type == 'percent') {
            $rule['rate'] = 'required|numeric|gt:0|lt:100';
        }
        return $rule;
    }


    public function attributes()
    {
        return [
            'name' => trans('validation.attributes.tax_rate.name'),
            'rate' => trans('validation.attributes.tax_rate.rate'),
            'type' => trans('validation.attributes.tax_rate.type'),
        ];
    }
}
