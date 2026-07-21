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

class SendGridTransport extends AbstractTransport
{
    /**
     * SendGrid API 密钥
     */
    protected string $apiKey;

    /**
     * SendGrid API 端点
     */
    protected string $endpoint;

    /**
     * HTTP 客户端
     */
    protected Client $client;

    /**
     * 创建 SendGrid 传输实例
     *
     * @param string                        $apiKey
     * @param string                        $endpoint
     * @param EventDispatcherInterface|null $dispatcher
     * @param LoggerInterface|null          $logger
     */
    public function __construct(
        string $apiKey,
        string $endpoint = 'https://api.sendgrid.com',
        ?EventDispatcherInterface $dispatcher = null,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct($dispatcher, $logger);

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
            throw new \InvalidArgumentException('Message must be an instance of Symfony\Component\Mime\Email');
        }

        try {
            $response = $this->sendToSendGrid($email);
            $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('SendGrid 邮件发送失败', [
                'error'   => $e->getMessage(),
                'to'      => $this->getRecipients($email),
                'subject' => $email->getSubject(),
            ]);

            throw $e;
        }
    }

    /**
     * 发送邮件到 SendGrid
     *
     * @param Email $email
     * @return array
     * @throws GuzzleException
     */
    protected function sendToSendGrid(Email $email): array
    {
        $data = $this->buildSendGridPayload($email);

        $response = $this->client->post($this->endpoint . '/v3/mail/send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => $data,
        ]);

        $statusCode = $response->getStatusCode();
        $body       = $response->getBody()->getContents();

        return [
            'status_code' => $statusCode,
            'body'        => $body,
            'headers'     => $response->getHeaders(),
        ];
    }

    /**
     * 构建 SendGrid API 请求数据
     *
     * @param Email $email
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function buildSendGridPayload(Email $email): array
    {
        $from        = $email->getFrom();
        $fromAddress = array_keys($from)[0] ?? '';
        $fromName    = $from[$fromAddress]  ?? '';

        // 验证发件人地址
        if (empty($fromAddress)) {
            throw new \InvalidArgumentException('SendGrid 发件人地址不能为空');
        }

        if (! filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("SendGrid 发件人地址格式无效: {$fromAddress}");
        }

        // 检查是否是示例地址
        if (strpos($fromAddress, 'example.com') !== false) {
            throw new \InvalidArgumentException("SendGrid 不支持示例域名地址: {$fromAddress}。请在后台设置中配置真实的发件人邮箱地址。");
        }

        $to = $email->getTo();

        if (empty($to)) {
            throw new \InvalidArgumentException('SendGrid 收件人地址不能为空');
        }

        $personalizations = [];

        // 构建收件人信息
        $toList = [];
        foreach ($to as $address => $name) {
            $toList[] = [
                'email' => $address,
                'name'  => $name ?: null,
            ];
        }

        $personalization = [
            'to'      => $toList,
            'subject' => $email->getSubject(),
        ];

        // 处理抄送
        $cc = $email->getCc();
        if (! empty($cc)) {
            $ccList = [];
            foreach ($cc as $address => $name) {
                $ccList[] = [
                    'email' => $address,
                    'name'  => $name ?: null,
                ];
            }
            $personalization['cc'] = $ccList;
        }

        // 处理密送
        $bcc = $email->getBcc();
        if (! empty($bcc)) {
            $bccList = [];
            foreach ($bcc as $address => $name) {
                $bccList[] = [
                    'email' => $address,
                    'name'  => $name ?: null,
                ];
            }
            $personalization['bcc'] = $bccList;
        }

        $personalizations[] = $personalization;

        $data = [
            'personalizations' => $personalizations,
            'from'             => [
                'email' => $fromAddress,
                'name'  => $fromName ?: null,
            ],
            'content' => $this->buildContent($email),
        ];

        // 处理回复地址
        $replyTo = $email->getReplyTo();
        if (! empty($replyTo)) {
            $replyToAddress   = array_keys($replyTo)[0];
            $replyToName      = $replyTo[$replyToAddress] ?? '';
            $data['reply_to'] = [
                'email' => $replyToAddress,
                'name'  => $replyToName ?: null,
            ];
        }

        // 处理附件
        $attachments = $email->getAttachments();
        if (! empty($attachments)) {
            $data['attachments'] = $this->processAttachments($attachments);
        }

        return $data;
    }

    /**
     * 构建邮件内容
     *
     * @param Email $email
     * @return array
     */
    protected function buildContent(Email $email): array
    {
        $content = [];

        $textBody = $email->getTextBody();
        if ($textBody) {
            $content[] = [
                'type'  => 'text/plain',
                'value' => $textBody,
            ];
        }

        $htmlBody = $email->getHtmlBody();
        if ($htmlBody) {
            $content[] = [
                'type'  => 'text/html',
                'value' => $htmlBody,
            ];
        }

        return $content;
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
                'content'     => base64_encode($attachment->getBody()),
                'type'        => $attachment->getContentType(),
                'filename'    => $attachment->getFilename(),
                'disposition' => 'attachment',
            ];
        }

        return $processedAttachments;
    }

    /**
     * 处理 SendGrid API 响应
     *
     * @param  array      $response
     * @throws \Exception
     */
    protected function handleResponse(array $response): void
    {
        $statusCode = $response['status_code'];

        if ($statusCode >= 200 && $statusCode < 300) {
            Log::info('SendGrid 邮件发送成功', [
                'status_code' => $statusCode,
                'message_id'  => $response['headers']['X-Message-Id'][0] ?? null,
            ]);
        } else {
            $body         = $response['body'];
            $errorData    = json_decode($body, true);
            $errorMessage = 'SendGrid API 调用失败';

            if (isset($errorData['errors']) && is_array($errorData['errors'])) {
                $errors = array_map(function ($error) {
                    return $error['message'] ?? 'Unknown error';
                }, $errorData['errors']);
                $errorMessage = implode('; ', $errors);
            }

            throw new \Exception("SendGrid 错误 [{$statusCode}]: {$errorMessage}");
        }
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
        return 'sendgrid';
    }
}
