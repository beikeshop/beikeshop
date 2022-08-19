<?php
/**
 * PageRequest.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
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
            'descriptions.*.name' => 'required|string|min:3|max:32',
            // 'descriptions.*.description' => 'required|string',
            'brand_id' => 'int',
            'sku' => 'required|string',
            'price' => 'required|float',
        ];
    }
}
