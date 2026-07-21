<?php

/**
 * MailEventListener.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2025-07-22
 */

namespace Beike\Listeners;

use Beike\Models\MailLog;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Email;

class MailEventListener
{
    /**
     * 邮件发送前事件处理
     */
    public function handleMessageSending(MessageSending $event)
    {
        try {
            $message = $event->message;

            // 获取邮件信息
            $mailData = $this->extractMailData($message);

            // 创建邮件记录
            $mailLog = MailLog::create([
                'to_email'    => $mailData['to_email'],
                'to_name'     => $mailData['to_name'],
                'from_email'  => $mailData['from_email'],
                'from_name'   => $mailData['from_name'],
                'subject'     => $mailData['subject'],
                'content'     => $mailData['content'],
                'mail_type'   => $this->detectMailType($mailData['subject'], $mailData['content']),
                'transport'   => $this->getCurrentTransport(),
                'status'      => MailLog::STATUS_PENDING,
                'headers'     => $mailData['headers'],
                'attachments' => $mailData['attachments'],
            ]);

            // 将邮件记录ID存储到消息头中，以便发送后更新状态
            $message->getHeaders()->addTextHeader('X-Mail-Log-ID', $mailLog->id);

        } catch (\Exception $e) {
            Log::error('邮件发送前记录失败: ' . $e->getMessage(), [
                'exception' => $e,
                'message'   => $message ?? null,
            ]);
        }
    }

    /**
     * 邮件发送后事件处理
     */
    public function handleMessageSent(MessageSent $event)
    {
        try {
            $message = $event->message;

            // 获取邮件记录ID
            $mailLogIdHeader = $message->getHeaders()->get('X-Mail-Log-ID');
            if (! $mailLogIdHeader) {
                return;
            }

            $mailLogId = $mailLogIdHeader->getBody();
            $mailLog   = MailLog::find($mailLogId);

            if ($mailLog) {
                $mailLog->update([
                    'status'  => MailLog::STATUS_SENT,
                    'sent_at' => now(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('邮件发送后记录更新失败: ' . $e->getMessage(), [
                'exception' => $e,
                'message'   => $message ?? null,
            ]);
        }
    }

    /**
     * 邮件发送失败事件处理
     */
    public function handleMessageSendingFailed($event)
    {
        try {
            // 这里处理发送失败的情况
            // 由于Laravel的邮件事件系统限制，我们需要通过其他方式捕获失败
            Log::warning('邮件发送失败事件触发', ['event' => $event]);

        } catch (\Exception $e) {
            Log::error('邮件发送失败记录处理失败: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    /**
     * 从邮件消息中提取数据
     */
    private function extractMailData($message): array
    {
        $data = [
            'to_email'    => '',
            'to_name'     => '',
            'from_email'  => '',
            'from_name'   => '',
            'subject'     => '',
            'content'     => '',
            'headers'     => [],
            'attachments' => [],
        ];

        try {
            // 获取收件人信息
            $toAddresses = $message->getTo();
            if (! empty($toAddresses)) {
                $firstTo          = reset($toAddresses);
                $data['to_email'] = $firstTo->getAddress();
                $data['to_name']  = $firstTo->getName() ?: '';
            }

            // 获取发件人信息
            $fromAddresses = $message->getFrom();
            if (! empty($fromAddresses)) {
                $firstFrom          = reset($fromAddresses);
                $data['from_email'] = $firstFrom->getAddress();
                $data['from_name']  = $firstFrom->getName() ?: '';
            }

            // 获取主题
            $data['subject'] = $message->getSubject() ?: '';

            // 获取邮件内容
            if ($message instanceof Email) {
                $htmlBody        = $message->getHtmlBody();
                $textBody        = $message->getTextBody();
                $data['content'] = $htmlBody ?: $textBody ?: '';
            }

            // 获取头信息
            $headers = [];
            foreach ($message->getHeaders()->all() as $header) {
                $headers[$header->getName()] = $header->getBodyAsString();
            }
            $data['headers'] = $headers;

            // 获取附件信息
            $attachments = [];
            if ($message instanceof Email) {
                foreach ($message->getAttachments() as $attachment) {
                    $attachments[] = [
                        'name' => $attachment->getFilename(),
                        'size' => strlen($attachment->getBody()),
                        'type' => $attachment->getMediaType(),
                    ];
                }
            }
            $data['attachments'] = $attachments;

        } catch (\Exception $e) {
            Log::error('提取邮件数据失败: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $data;
    }

    /**
     * 根据邮件主题和内容检测邮件类型
     */
    private function detectMailType(string $subject, string $content): string
    {
        $subject = strtolower($subject);
        $content = strtolower($content);

        // 根据主题关键词判断邮件类型
        if (strpos($subject, '注册') !== false || strpos($subject, 'registration') !== false) {
            return 'customer_registration';
        }

        if (strpos($subject, '忘记密码') !== false || strpos($subject, 'forgotten') !== false || strpos($subject, 'password') !== false) {
            return 'customer_forgotten';
        }

        if (strpos($subject, '订单') !== false || strpos($subject, 'order') !== false) {
            if (strpos($subject, '新') !== false || strpos($subject, 'new') !== false) {
                return 'order_new';
            }

            return 'order_update';
        }

        if (strpos($subject, '退货') !== false || strpos($subject, 'return') !== false || strpos($subject, 'rma') !== false) {
            return 'rma_new';
        }

        if (strpos($subject, '管理员') !== false || strpos($subject, 'admin') !== false) {
            return 'admin_forgotten';
        }

        return 'other';
    }

    /**
     * 获取当前邮件传输方式
     */
    private function getCurrentTransport(): string
    {
        try {
            // 获取当前默认的邮件驱动
            $defaultMailer = Config::get('mail.default', 'smtp');

            // 获取对应驱动的传输方式
            $mailerConfig = Config::get("mail.mailers.{$defaultMailer}");

            if (is_array($mailerConfig) && isset($mailerConfig['transport'])) {
                return $mailerConfig['transport'];
            }

            // 如果没有明确的传输方式配置，返回驱动名称
            return $defaultMailer;

        } catch (\Exception $e) {
            Log::error('获取邮件传输方式失败: ' . $e->getMessage());

            return 'unknown';
        }
    }
}
