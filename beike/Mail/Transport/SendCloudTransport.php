<?php

namespace Beike\Mail\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;

class SendCloudTransport extends AbstractTransport
{
    /**
     * SendCloud API 用户名
     */
    protected string $apiUser;

    /**
     * SendCloud API 密钥
     */
    protected string $apiKey;

    /**
     * SendCloud API 端点
     */
    protected string $endpoint;

    /**
     * HTTP 客户端
     */
    protected Client $client;

    /**
     * 创建 SendCloud 传输实例
     *
     * @param string                        $apiUser
     * @param string                        $apiKey
     * @param string                        $endpoint
     * @param EventDispatcherInterface|null $dispatcher
     * @param LoggerInterface|null          $logger
     */
    public function __construct(
        string $apiUser,
        string $apiKey,
        string $endpoint = 'https://api.sendcloud.net',
        ?EventDispatcherInterface $dispatcher = null,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct($dispatcher, $logger);

        $this->apiUser  = $apiUser;
        $this->apiKey   = $apiKey;
        $this->endpoint = rtrim($endpoint, '/');
        $this->client   = new Client([
            'timeout' => 30,
            'verify'  => true,
        ]);
    }

    /**
     * 发送邮件
     *
     * @param SentMessage $message
     * @return void
     */
    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (! $email instanceof Email) {
            throw new \InvalidArgumentException(__('mail.sendcloud_invalid_message_type'));
        }

        try {
            $response = $this->sendToSendCloud($email);
            $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error(__('mail.sendcloud_send_failed'), [
                'error'   => $e->getMessage(),
                'to'      => $this->getRecipients($email),
                'subject' => $email->getSubject(),
            ]);

            throw $e;
        }
    }

    /**
     * 发送邮件到 SendCloud
     *
     * @param Email $email
     * @return array
     * @throws GuzzleException
     */
    protected function sendToSendCloud(Email $email): array
    {
        $data = $this->buildSendCloudPayload($email);

        $response = $this->client->post($this->endpoint . '/apiv2/mail/send', [
            'form_params' => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 构建 SendCloud API 请求数据
     *
     * @param Email $email
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function buildSendCloudPayload(Email $email): array
    {
        $from = $email->getFrom();

        // 获取发件人信息
        if (empty($from)) {
            throw new \InvalidArgumentException(__('mail.sendcloud_from_address_empty'));
        }

        [$fromAddress, $fromName] = rescue(
            fn () => [$email->getFrom()[0]->getAddress(), $email->getFrom()[0]->getName() ?? ''],
            fn () => throw new \InvalidArgumentException(__('mail.sendcloud_from_address_empty')),
            false
        );

        // 验证发件人地址格式
        if (! filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(__('mail.sendcloud_from_address_invalid', ['address' => $fromAddress]));
        }

        // 检查是否是示例地址
        if (str_contains($fromAddress, 'example.com')) {
            throw new \InvalidArgumentException(__('mail.sendcloud_example_domain_not_supported', ['address' => $fromAddress]));
        }

        $to = $email->getTo();
        if (empty($to)) {
            throw new \InvalidArgumentException(__('mail.sendcloud_to_address_empty'));
        }

        foreach ($to as $address) {
            if (! filter_var($address->getAddress(), FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException(__('mail.sendcloud_to_address_invalid', ['address' => $address->getAddress()]));
            }
            $toAddresses[] = $address->getAddress();
        }

        if (empty($toAddresses)) {
            throw new \InvalidArgumentException(__('mail.sendcloud_to_address_empty'));
        }

        $data = [
            'apiUser'  => $this->apiUser,
            'apiKey'   => $this->apiKey,
            'from'     => $fromAddress,
            'fromName' => $fromName,
            'to'       => implode(';', $toAddresses),
            'subject'  => $email->getSubject(),
        ];

        // 处理邮件内容
        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();

        if ($htmlBody) {
            $data['html'] = $htmlBody;
        }

        if ($textBody) {
            $data['plain'] = $textBody;
        }

        // 处理抄送
        $cc = $email->getCc();
        if (! empty($cc)) {
            $data['cc'] = implode(';', array_keys($cc));
        }

        // 处理密送
        $bcc = $email->getBcc();
        if (! empty($bcc)) {
            $data['bcc'] = implode(';', array_keys($bcc));
        }

        // 处理回复地址
        $replyTo = $email->getReplyTo();
        if (! empty($replyTo)) {
            $data['replyTo'] = array_keys($replyTo)[0];
        }

        // 处理附件
        $attachments = $email->getAttachments();
        if (! empty($attachments)) {
            $data['attachments'] = $this->processAttachments($attachments);
        }

        return $data;
    }

    /**
     * 处理附件
     *
     * @param array $attachments
     * @return array
     */
    protected function processAttachments(array $attachments): array
    {
        $processedAttachments = [];

        foreach ($attachments as $attachment) {
            $processedAttachments[] = [
                'name'    => $attachment->getFilename(),
                'content' => base64_encode($attachment->getBody()),
                'type'    => $attachment->getContentType(),
            ];
        }

        return $processedAttachments;
    }

    /**
     * 处理 SendCloud API 响应
     *
     * @param  array      $response
     * @throws \Exception
     */
    protected function handleResponse(array $response): void
    {
        if (! isset($response['result']) || ! $response['result']) {
            $message    = $response['message']    ?? __('mail.sendcloud_api_call_failed');
            $statusCode = $response['statusCode'] ?? 500;

            throw new \Exception(__('mail.sendcloud_api_error', ['status_code' => $statusCode, 'message' => $message]));
        }

        Log::info(__('mail.sendcloud_send_success'), [
            'message_id' => $response['info']['emailIdList'] ?? null,
        ]);
    }

    /**
     * 获取收件人列表
     *
     * @param Email $email
     * @return array
     */
    protected function getRecipients(Email $email): array
    {
        return array_keys($email->getTo());
    }

    /**
     * 获取传输名称
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'sendcloud';
    }
}
