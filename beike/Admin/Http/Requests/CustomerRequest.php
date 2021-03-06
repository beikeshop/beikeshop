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
        $rules = [
            'name' => 'required|max:64',
            'email' => 'required|email:rfc,dns|unique:customers',
            'customer_group_id' => 'required|exists:customer_groups,id',
        ];
        if (!$this->id) {
            $rules['password'] = 'required|max:64';
        } else {
            $rules['email'] = 'required|email:rfc,dns|unique:customers,email,' . $this->id;
        }
        return $rules;
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
