<?php

namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'descriptions.*.name'             => 'required|max:255',
            'descriptions.*.meta_title'       => 'max:191',
            'descriptions.*.meta_keywords'    => 'max:191',
            'descriptions.*.meta_description' => 'max:191',
        ];
    }

    public function attributes(): array
    {
        return [
            'descriptions.*.name'             => trans('category.name'),
            'descriptions.*.meta_title'       => 'Meta Title',
            'descriptions.*.meta_keywords'    => 'Meta Keywords',
            'descriptions.*.meta_description' => 'Meta Description',
        ];
    }
}
