<?php
/**
 * PageRequest.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-19 21:58:20
 * @modified   2022-08-19 21:58:20
 */

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        return [
            'descriptions.*.name' => 'required|string|min:3|max:128',
            'brand_id'            => 'int',
            'skus.*.sku'          => 'required|string',
            'skus.*.price'        => 'required|numeric',
            'skus.*.cost_price'   => 'numeric',
            'skus.*.quantity'     => 'numeric|max:2000000000',
        ];
    }

    public function attributes()
    {
        return [
            'descriptions.*.name' => trans('product.name'),
            'brand_id'            => trans('product.brand'),
            'skus.*.sku'          => trans('product.sku'),
            'skus.*.price'        => trans('product.price'),
            'skus.*.cost_price'   => trans('product.cost_price'),
            'skus.*.quantity'     => trans('product.quantity'),
        ];
    }
}
