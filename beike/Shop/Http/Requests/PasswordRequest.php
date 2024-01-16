<?php
/**
 * PasswordRequest.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-12-14 14:20:47
 * @modified   2023-12-14 14:20:47
 */

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'old_password' => 'required|current_password',
            'new_password' => 'required|confirmed|min:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'old_password' => trans('shop/account/password.old_password'),
            'new_password' => trans('shop/account/password.new_password'),
        ];
    }
}
