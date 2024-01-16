<?php
/**
 * RmaRequest.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-03 11:17:04
 * @modified   2022-08-03 11:17:04
 */

namespace Beike\Admin\Http\Requests;

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
            'order_id'         => 'required|exists:orders,id',
            'order_product_id' => 'required|exists:order_products,id',
            'customer_id'      => 'required|exists:customers,id',
            'quantity'         => 'required',
            'opened'           => 'required',
            'rma_reason_id'    => 'required|exists:rma_reasons,id',
            'type'             => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'order_id'         => trans('rma.order_id'),
            'order_product_id' => trans('rma.order_product_id'),
            'customer_id'      => trans('rma.customer_id'),
            'quantity'         => trans('rma.quantity'),
            'opened'           => trans('rma.opened'),
            'rma_reason_id'    => trans('rma.rma_reason_id'),
            'type'             => trans('rma.type'),
        ];
    }
}
