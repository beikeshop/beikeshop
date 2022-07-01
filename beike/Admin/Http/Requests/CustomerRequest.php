<?php
/**
 * CustomerRequest.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-01 15:17:04
 * @modified   2022-07-01 15:17:04
 */

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => 'required|max:64',
            'email' => 'required|email:rfc,dns|unique:customers',
            'password' => 'required|max:64',
            'customer_group_id' => 'required|unique:customer_groups',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '姓名',
            'email' => 'Email',
            'password' => '密码',
            'customer_group_id' => '会员组',
        ];
    }
}
