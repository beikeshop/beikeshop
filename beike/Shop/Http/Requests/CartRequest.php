<?php
/**
 * CartRequest.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-26 14:21:32
 * @modified   2022-08-26 14:21:32
 */

namespace Beike\Shop\Http\Requests;

use Beike\Models\ProductSku;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
    public function rules()
    {
        $skuId = (int) $this->get('sku_id');

        return [
            'sku_id'   => 'required|int',
            'quantity' => ['required', 'int', function ($attribute, $value, $fail) use ($skuId) {
                $skuQuantity = ProductSku::query()->where('id', $skuId)->value('quantity');
                if ($value > $skuQuantity) {
                    $fail(trans('cart.stock_out'));
                }
            }],
            'buy_now'  => 'bool',
        ];
    }

    public function attributes()
    {
        return [
            'sku_id'   => trans('cart.sku_id'),
            'quantity' => trans('cart.quantity'),
        ];
    }
}
