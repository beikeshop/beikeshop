<?php
/**
 * UploadRequest.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-10 14:51:27
 * @modified   2022-08-10 14:51:27
 */

namespace Beike\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'file' => 'required|mimes:jpg,png,jpeg,gif,webp,mp4',
            'type' => 'required|alpha_dash|max:255',
        ];
    }

    /**
     * 配置验证器实例
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $file = $this->file('file');
            $type = $this->get('type', '');

            if ($file) {
                // 验证文件名安全性
                $originalName = $file->getClientOriginalName();
                if (!$this->isValidFileName($originalName)) {
                    $validator->errors()->add('file', trans('shop/file.invalid_filename'));
                }
            }

            // 验证类型参数安全性
            if ($type && !$this->isValidType($type)) {
                $validator->errors()->add('type', trans('shop/file.invalid_type'));
            }
        });
    }

    /**
     * 验证文件名是否安全
     */
    private function isValidFileName(string $fileName): bool
    {
        // 检查文件名长度
        if (strlen($fileName) > 255 || empty(trim($fileName))) {
            return false;
        }

        // 检查危险字符
        if (preg_match('#[<>:"|?*\x00-\x1f]#', $fileName)) {
            return false;
        }

        // 检查路径遍历模式
        if (str_contains($fileName, '..') || str_contains($fileName, '/') || str_contains($fileName, '\\')) {
            return false;
        }

        return true;
    }

    /**
     * 验证类型参数是否安全
     */
    private function isValidType(string $type): bool
    {
        // 只允许字母、数字、下划线和连字符
        return preg_match('/^[a-zA-Z0-9_-]+$/', $type);
    }
}
