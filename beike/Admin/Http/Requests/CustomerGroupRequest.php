<?php
/**
 * CustomerGroupRequest.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-01 14:17:04
 * @modified   2022-07-01 14:17:04
 */

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupRequest extends FormRequest
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
            'name.*' => 'required|max:64',
            'level'  => 'required|max:16',
        ];
    }

    public function attributes()
    {
        return [
            'descriptions.*.name' => trans('customer_group.name'),
            'level'               => trans('customer_group.level'),
        ];
    }
}
