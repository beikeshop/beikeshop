<?php

/**
 * MailLog.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2025-07-22
 */

namespace Beike\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $fillable = [
        'to_email',
        'to_name',
        'from_email',
        'from_name',
        'subject',
        'content',
        'mail_type',
        'transport',
        'status',
        'error_message',
        'headers',
        'attachments',
        'sent_at',
    ];

    protected $casts = [
        'headers'     => 'array',
        'attachments' => 'array',
        'sent_at'     => 'datetime',
    ];

    /**
     * 状态常量
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    /**
     * 获取状态文本
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => __('admin/mail_log.status_pending'),
            self::STATUS_SENT    => __('admin/mail_log.status_sent'),
            self::STATUS_FAILED  => __('admin/mail_log.status_failed'),
            default              => __('admin/mail_log.unknown_status')
        };
    }

    /**
     * 获取状态颜色类
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_SENT    => 'success',
            self::STATUS_FAILED  => 'danger',
            default              => 'secondary'
        };
    }

    /**
     * 获取邮件类型文本
     */
    public function getMailTypeTextAttribute(): string
    {
        return match ($this->mail_type) {
            'customer_registration' => __('admin/mail_log.type_customer_registration'),
            'customer_forgotten'    => __('admin/mail_log.type_customer_forgotten'),
            'order_new'             => __('admin/mail_log.type_order_new'),
            'order_update'          => __('admin/mail_log.type_order_update'),
            'rma_new'               => __('admin/mail_log.type_rma_new'),
            'admin_forgotten'       => __('admin/mail_log.type_admin_forgotten'),
            default                 => $this->mail_type ?: __('admin/mail_log.unknown_type')
        };
    }

    /**
     * 获取传输方式文本
     */
    public function getTransportTextAttribute(): string
    {
        return match ($this->transport) {
            'smtp'      => __('admin/mail_log.transport_smtp'),
            'sendmail'  => __('admin/mail_log.transport_sendmail'),
            'mailgun'   => __('admin/mail_log.transport_mailgun'),
            'sendcloud' => __('admin/mail_log.transport_sendcloud'),
            'log'       => __('admin/mail_log.transport_log'),
            'array'     => __('admin/mail_log.transport_array'),
            'ses'       => __('admin/mail_log.transport_ses'),
            'postmark'  => __('admin/mail_log.transport_postmark'),
            default     => $this->transport ?: __('admin/mail_log.unknown_transport')
        };
    }

    /**
     * 获取简短内容
     */
    public function getShortContentAttribute(): string
    {
        if (empty($this->content)) {
            return '';
        }

        $content = strip_tags($this->content);

        return mb_strlen($content) > 100 ? mb_substr($content, 0, 100) . '...' : $content;
    }

    /**
     * 按状态筛选
     */
    public function scopeByStatus($query, $status)
    {
        if (! empty($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    /**
     * 按邮件类型筛选
     */
    public function scopeByMailType($query, $mailType)
    {
        if (! empty($mailType)) {
            return $query->where('mail_type', $mailType);
        }

        return $query;
    }

    /**
     * 按传输方式筛选
     */
    public function scopeByTransport($query, $transport)
    {
        if (! empty($transport)) {
            return $query->where('transport', $transport);
        }

        return $query;
    }

    /**
     * 按收件人筛选
     */
    public function scopeByRecipient($query, $email)
    {
        if (! empty($email)) {
            return $query->where('to_email', 'like', "%{$email}%");
        }

        return $query;
    }

    /**
     * 按时间范围筛选
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        if (! empty($startDate)) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if (! empty($endDate)) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query;
    }

    /**
     * 获取所有邮件类型选项
     */
    public static function getMailTypeOptions(): array
    {
        return [
            'customer_registration' => __('admin/mail_log.type_customer_registration'),
            'customer_forgotten'    => __('admin/mail_log.type_customer_forgotten'),
            'order_new'             => __('admin/mail_log.type_order_new'),
            'order_update'          => __('admin/mail_log.type_order_update'),
            'rma_new'               => __('admin/mail_log.type_rma_new'),
            'admin_forgotten'       => __('admin/mail_log.type_admin_forgotten'),
        ];
    }

    /**
     * 获取所有状态选项
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => __('admin/mail_log.status_pending'),
            self::STATUS_SENT    => __('admin/mail_log.status_sent'),
            self::STATUS_FAILED  => __('admin/mail_log.status_failed'),
        ];
    }

    /**
     * 获取所有传输方式选项
     */
    public static function getTransportOptions(): array
    {
        return [
            'smtp'      => __('admin/mail_log.transport_smtp'),
            'sendmail'  => __('admin/mail_log.transport_sendmail'),
            'mailgun'   => __('admin/mail_log.transport_mailgun'),
            'sendcloud' => __('admin/mail_log.transport_sendcloud'),
            'log'       => __('admin/mail_log.transport_log'),
            'array'     => __('admin/mail_log.transport_array'),
            'ses'       => __('admin/mail_log.transport_ses'),
            'postmark'  => __('admin/mail_log.transport_postmark'),
        ];
    }
}
