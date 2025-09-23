<?php
/**
 * UploadRequest.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-22 14:51:27
 * @modified   2022-07-22 14:51:27
 */

namespace Beike\Admin\Http\Requests;

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
            'file' => 'required|file|mimes:jpg,png,jpeg,gif,webp,mp4,pdf,doc,docx,xls,xlsx,ppt,pptx',
            'path' => 'nullable|string|max:255',
        ];
    }

    /**
     * 配置验证器实例
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $file = $this->file('file');
            $path = $this->get('path', '');

            if ($file) {
                // 验证文件名安全性
                $originalName = $file->getClientOriginalName();
                if (!$this->isValidFileName($originalName)) {
                    $validator->errors()->add('file', trans('admin/file_manager.invalid_filename'));
                }

                // 验证MIME类型
                if (!$this->isValidMimeType($file)) {
                    $validator->errors()->add('file', trans('admin/file_manager.upload_type_fail'));
                }
            }

            // 验证路径安全性
            if ($path && !$this->isValidPath($path)) {
                $validator->errors()->add('path', trans('admin/file_manager.invalid_path'));
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
     * 验证MIME类型
     */
    private function isValidMimeType($file): bool
    {
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'video/mp4',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ];

        return in_array($file->getMimeType(), $allowedMimeTypes);
    }

    /**
     * 验证路径是否安全
     */
    private function isValidPath(string $path): bool
    {
        // 检查路径遍历
        if (str_contains($path, '..') || str_contains($path, '\\')) {
            return false;
        }

        // 检查危险字符
        if (preg_match('#[<>:"|?*\x00-\x1f]#', $path)) {
            return false;
        }

        return true;
    }
}
