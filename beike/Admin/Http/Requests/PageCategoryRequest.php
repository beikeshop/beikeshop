<?php
/**
 * PageCategoryRequest.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-10 16:04:14
 * @modified   2023-02-10 16:04:14
 */

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageCategoryRequest extends FormRequest
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
            'descriptions.*.locale'  => 'required|string',
            'descriptions.*.title'   => 'required|string|min:3|max:32',
            'descriptions.*.summary' => 'string',
        ];
    }

    public function attributes()
    {
        return [
            'title'   => trans('page_category.title'),
            'summary' => trans('page_category.summary'),
        ];
    }
}
