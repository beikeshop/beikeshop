<?php

/*
 * @FilePath: AdminMjAiImageController.php
 *
 * @copyright     2025 beikeshop.com - All Rights Reserved.
 * @link: https://beikeshop.com
 * @Author: pu shuo <pushuo@guangda.work>
 * @Date: 2024-10-11 11:12:56
 * @LastEditTime: 2025-01-03 14:11:05
 */

namespace Plugin\BkAi\Controllers;

use Beike\Admin\Http\Controllers\Controller;
use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMjAiImageController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function saveSetting(Request $request): JsonResponse
    {
        SettingRepo::storeValue('setting', $request->all(), 'mj_ai_image', 'plugin');

        return json_success('保存成功');
    }

    public function generateImage(Request $request)
    {
        $params = $request->all();

        $url = 'https://beikeshop.cn/api/ai/img';

        $data = [
            'domain'     => $request->getHost(),
            'code'       => 'bk_ai',
            'model_name' => 'wanx-v1',
            'prompt'     => $params['prompt'],
            'style'      => $params['style'],
            'size'       => $params['size'],
            'n'          => $params['n'],
        ];
        // 初始化cURL会话
        $ch = curl_init();
        // 设置cURL选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: BkAiClient/1.0 (+https://beikeshop.com)',
        ]);

        // 执行cURL会话
        $response = curl_exec($ch);
        // 检查是否有错误发生
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        // 关闭cURL资源
        curl_close($ch);

        return response()->json($response);
    }

    public function checkImage(Request $request)
    {
        $task_id = $request->input('task_id');

        $url = 'https://beikeshop.cn/api/ai/img_tasks';

        $data = [
            'domain'  => $request->getHost(),
            'task_id' => $task_id,
        ];
        // 初始化cURL会话
        $ch = curl_init();
        // 设置cURL选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: BkAiClient/1.0 (+https://beikeshop.com)',
        ]);

        // 执行cURL会话
        $response = curl_exec($ch);
        // 检查是否有错误发生
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        // 关闭cURL资源
        curl_close($ch);

        return response()->json($response);
    }

    public function saveImage(Request $request)
    {
        // 获取传入的 URL 列表和保存路径
        $urls     = $request->get('urls');  // 传入的 URL 数组
        $savePath = $request->get('path');

        // 确保 URLs 参数有效
        if (empty($urls) || ! is_array($urls)) {
            return response()->json(['error' => 'URLs 参数无效'], 400);
        }

        $savedFiles = [];

        foreach ($urls as $url) {
            // 获取每个 URL 的文件内容
            $fileContent = file_get_contents($url);

            // 确保文件内容有效
            if (! $fileContent) {
                return response()->json(['error' => "无法获取文件: $url"], 400);
            }

            // 生成文件名
            $fileName = 'ai_image_' . time() . rand(1000, 9999) . '.png';  // 为每个文件生成唯一的名称

            // 拼接保存路径
            $filePath = public_path('catalog' . $savePath . '/' . $fileName);

            // 保存文件到本地
            $result = file_put_contents($filePath, $fileContent);

            if ($result === false) {
                return response()->json(['error' => "文件保存失败: $url"], 500);
            }

            // 记录保存的文件路径
            $savedFiles[] = $filePath;
        }

        return json_success(trans('common.created_success'), ['saved_files' => $savedFiles]);
    }
}
