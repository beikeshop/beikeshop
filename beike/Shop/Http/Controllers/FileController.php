<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Shop\Http\Requests\UploadRequest;

class FileController extends Controller
{
    public function store(UploadRequest $request)
    {
        $file = $request->file('file');
        $type = $request->get('type', 'default');

        $type = preg_replace('/[^a-zA-Z0-9_-]/', '', $type);
        if (empty($type)) {
            $type = 'default';
        }

        // 清理类型参数，防止路径遍历
        $type = $this->sanitizeType($type);

        $path = $file->store($type, 'upload');

        $data = [
            'url'   => asset('upload/' . $path),
            'value' => 'upload/' . $path,
        ];

        $data = hook_filter('file.store.data', $data);

        // 记录上传日志
        \Log::info('Shop file uploaded', [
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'customer_id' => current_customer()->id ?? null,
            'ip' => $request->ip()
        ]);

        return json_success(trans('shop/file.uploaded_success'), $data);
    }

    /**
     * 清理类型参数
     */
    private function sanitizeType(string $type): string
    {
        // 移除危险字符
        $type = preg_replace('/[^a-zA-Z0-9_-]/', '', $type);

        // 防止路径遍历
        $type = str_replace(['..', '/', '\\'], '', $type);

        return $type;
    }
}
