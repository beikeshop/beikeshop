<?php

namespace Beike\Admin\Services;
use Illuminate\Support\Facades\Http;

class UniPushService
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @return UniPushService
     */
    public static function getInstance(): UniPushService
    {
        return new self();
    }


    /**
     * 推送已发货消息
     *
     * @param $message
     * @throws \Exception
     */
    public function pushOrderStatus($message)
    {
        $cloudFunctionUrl = system_setting('base.unipush_api_url');
        if (empty($cloudFunctionUrl)) {
            return json_fail(__('admin/app_push.api_url_err'));
        }

        // 生成10-32位之间的随机字符串
        $requestId = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, rand(10, 32));

        $requestData = [
            "push_clientid" => $message['push_clientid'] ?? '',
            "request_id" => $requestId,
            "force_notification" => true,
            "title" => $message['title'] ?? '默认标题',
            "badge" => "+1",
            "content" => $message['content'] ?? '默认内容',
            "payload" => [
                "product_id" => $message['link']['type'] == 'product' ? $message['link']['value'] : '',
                "category_id" => $message['link']['type'] == 'category' ? $message['link']['value'] : '',
                "post_id" => $message['link']['type'] == 'post' ? $message['link']['value'] : '',
                "order_id" => $message['link']['type'] == 'order' ? $message['link']['value'] : '',
                "custom" => $message['link']['type'] == 'custom' ? $message['link']['value'] : '',
                "customer_id" => $message['customer_id'] ?? '',
            ]
        ];

        try {
            $response = $this->httpClient->post($cloudFunctionUrl, $requestData);
            $result = $response->json();

            if ($response->successful() && ($result['errCode'] ?? 0) == 0) {
                return json_success('推送成功');
            } else {
                $errMsg = $result['errMsg'] ?? '未知错误';
                return json_fail($errMsg);
            }
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
