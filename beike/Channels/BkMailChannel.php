<?php

namespace Beike\Channels;

use Beike\Models\MailLog;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BkMailChannel extends MailChannel
{
    public function send($notifiable, Notification $notification)
    {
        try {
            // 执行邮件发送
            parent::send($notifiable, $notification);

        } catch (\Exception $e) {
            // 记录到文件日志（保持现有功能）
            error_log(
                date('[Y-m-d H:i:s]') . ' MAIL ERROR: ' . $e->getMessage() . PHP_EOL,
                3,
                storage_path('logs/mail.log')
            );

            // 尝试通过邮件信息匹配找到对应的记录并更新状态
            $this->handleMailFailure($notifiable, $notification, $e->getMessage());

            // 不抛出异常，静默失败
        }
    }

    /**
     * 处理邮件发送失败
     */
    private function handleMailFailure($notifiable, $notification, $errorMessage)
    {
        try {
            // 尝试通过邮件信息匹配找到最近的 pending 记录
            $mailLog = $this->findPendingMailLog($notifiable, $notification);

            if ($mailLog) {
                // 更新现有记录状态为失败
                $this->updateMailLogStatus($mailLog->id, $errorMessage);
            } else {
                // 如果没有找到对应的记录，创建新的失败记录
                $this->createFailedMailLog($notifiable, $notification, $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('处理邮件失败记录失败: ' . $e->getMessage(), [
                'notifiable'   => $notifiable->email ?? 'unknown',
                'notification' => get_class($notification),
                'error'        => $errorMessage,
            ]);
        }
    }

    /**
     * 查找对应的待发送邮件记录
     */
    private function findPendingMailLog($notifiable, $notification)
    {
        try {
            $mailData = $this->extractMailDataFromNotification($notifiable, $notification);

            // 查找最近创建的相同收件人和主题的 pending 记录
            $query = MailLog::where('status', MailLog::STATUS_PENDING)
                ->where('to_email', $mailData['to_email'])
                ->orderBy('created_at', 'desc');

            // 如果有主题，也按主题匹配
            if (! empty($mailData['subject'])) {
                $query->where('subject', $mailData['subject']);
            }

            // 查找最近5分钟内创建的记录
            $query->where('created_at', '>=', now()->subMinutes(5));

            return $query->first();
        } catch (\Exception $e) {
            Log::error('查找待发送邮件记录失败: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * 更新邮件记录状态为失败
     */
    private function updateMailLogStatus($mailLogId, $errorMessage)
    {
        try {
            $mailLog = MailLog::find($mailLogId);
            if ($mailLog) {
                $mailLog->update([
                    'status'        => MailLog::STATUS_FAILED,
                    'error_message' => $errorMessage,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('更新邮件记录状态失败: ' . $e->getMessage(), [
                'mail_log_id' => $mailLogId,
                'error'       => $errorMessage,
            ]);
        }
    }

    /**
     * 创建失败的邮件记录
     */
    private function createFailedMailLog($notifiable, $notification, $errorMessage)
    {
        try {
            $mailData = $this->extractMailDataFromNotification($notifiable, $notification);

            MailLog::create([
                'to_email'      => $mailData['to_email'],
                'to_name'       => $mailData['to_name'],
                'from_email'    => $mailData['from_email'],
                'from_name'     => $mailData['from_name'],
                'subject'       => $mailData['subject'],
                'content'       => $mailData['content'],
                'mail_type'     => $mailData['mail_type'],
                'transport'     => $mailData['transport'],
                'status'        => MailLog::STATUS_FAILED,
                'error_message' => $errorMessage,
                'headers'       => $mailData['headers'],
                'attachments'   => $mailData['attachments'],
            ]);
        } catch (\Exception $e) {
            Log::error('创建失败邮件记录失败: ' . $e->getMessage(), [
                'notifiable'   => $notifiable->email ?? 'unknown',
                'notification' => get_class($notification),
                'error'        => $errorMessage,
            ]);
        }
    }

    /**
     * 从通知中提取邮件数据
     */
    private function extractMailDataFromNotification($notifiable, $notification)
    {
        $data = [
            'to_email'    => $notifiable->email ?? '',
            'to_name'     => $notifiable->name  ?? '',
            'from_email'  => '',
            'from_name'   => '',
            'subject'     => '',
            'content'     => '',
            'mail_type'   => 'other',
            'transport'   => $this->getCurrentTransport(),
            'headers'     => [],
            'attachments' => [],
        ];

        try {
            // 获取邮件消息
            $message = $notification->toMail($notifiable);

            if ($message) {
                // 获取发件人信息
                $from               = $message->from     ?? config('mail.from.address');
                $fromName           = $message->fromName ?? config('mail.from.name');
                $data['from_email'] = is_array($from) ? $from[0] : $from;
                $data['from_name']  = is_array($fromName) ? $fromName[0] : $fromName;

                // 获取主题
                $data['subject'] = $message->subject ?? '';

                // 获取内容
                if (method_exists($message, 'render')) {
                    $data['content'] = $message->render();
                }

                // 检测邮件类型
                $data['mail_type'] = $this->detectMailType($data['subject'], $data['content']);
            }
        } catch (\Exception $e) {
            Log::error('提取邮件数据失败: ' . $e->getMessage());
        }

        return $data;
    }

    /**
     * 检测邮件类型
     */
    private function detectMailType($subject, $content)
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
    private function getCurrentTransport()
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
