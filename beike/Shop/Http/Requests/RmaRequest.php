<?php
/**
 * RmaRequest.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-03 11:17:04
 * @modified   2022-08-03 11:17:04
 */

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RmaRequest extends FormRequest
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
            'order_id' => 'required|exists:orders,id',
            'order_product_id' => 'required|exists:order_products,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required',
            'opened' => 'required',
            'rma_reason_id' => 'required|exists:rma_reasons,id',
            'type' => 'required',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'order_id' => '订单',
            'order_product_id' => '订单商品',
            'customer_id' => '顾客',
            'quantity' => '数量',
            'opened' => '已拆包装',
            'rma_reason_id' => '退换货原因',
            'type' => '售后服务类型',
        ];
    }
}
