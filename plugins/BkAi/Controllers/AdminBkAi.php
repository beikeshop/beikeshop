<?php

/*
 * @FilePath: AdminBkAi.php
 *
 * @copyright     2025 beikeshop.com - All Rights Reserved.
 * @link: https://beikeshop.com
 * @Author: pu shuo <pushuo@guangda.work>
 * @Date: 2024-12-15 20:08:50
 * @LastEditTime: 2025-01-03 14:11:15
 */

namespace Plugin\BkAi\Controllers;

use Beike\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminBkAi extends Controller
{
    public function index()
    {
        $data = [];

        return view('BkAi::admin.bk_ai_index', $data);
    }

    // generate
    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');

        $url = 'https://beikeshop.cn/api/ai/txt';

        $data = [
            'domain'     => $request->getHost(),
            'code'       => 'bk_ai',
            'model_name' => 'qwen-turbo',
            'prompt'     => $prompt,
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

    // quota
    public function getQuota(Request $request)
    {
        $url = 'https://beikeshop.cn/api/ai/quota';

        $data = [
            'domain'     => $request->getHost(),
            'code'       => 'bk_ai',
            'model_name' => 'qwen-turbo',
        ];

        // 将参数数组转换为查询字符串
        $queryString = http_build_query($data);

        // 将查询字符串附加到 URL 上
        $fullUrl = $url . '?' . $queryString;

        $ch = curl_init($fullUrl);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 改用GET请求
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: BkAiClient/1.0 (+https://beikeshop.com)',
        ]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return response()->json($response);
    }
}
