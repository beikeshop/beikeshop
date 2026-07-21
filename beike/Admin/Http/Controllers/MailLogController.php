<?php

/**
 * MailLogController.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2025-07-22
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\MailLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailLogController extends Controller
{
    protected string $defaultRoute = 'mail_logs.index';

    /**
     * 邮件记录列表页面
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'mail_type', 'transport', 'recipient', 'start_date', 'end_date']);

        $query = MailLog::query()
            ->byStatus($filters['status'] ?? '')
            ->byMailType($filters['mail_type'] ?? '')
            ->byTransport($filters['transport'] ?? '')
            ->byRecipient($filters['recipient'] ?? '')
            ->byDateRange($filters['start_date'] ?? '', $filters['end_date'] ?? '')
            ->orderBy('id', 'desc');

        $mailLogs = $query->paginate(20)->appends($filters);

        $data = [
            'mail_logs'         => $mailLogs,
            'filters'           => $filters,
            'status_options'    => MailLog::getStatusOptions(),
            'mail_type_options' => MailLog::getMailTypeOptions(),
            'transport_options' => MailLog::getTransportOptions(),
            'total_count'       => MailLog::count(),
            'sent_count'        => MailLog::where('status', MailLog::STATUS_SENT)->count(),
            'failed_count'      => MailLog::where('status', MailLog::STATUS_FAILED)->count(),
            'pending_count'     => MailLog::where('status', MailLog::STATUS_PENDING)->count(),
        ];

        return view('admin::pages.mail_logs.index', $data);
    }

    /**
     * 邮件记录详情页面
     */
    public function show(MailLog $mailLog): View
    {
        $data = [
            'mail_log' => $mailLog,
        ];

        return view('admin::pages.mail_logs.show', $data);
    }

    /**
     * 删除邮件记录
     */
    public function destroy(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return json_fail(__('admin/mail_log.select_records_to_delete'));
        }

        try {
            MailLog::whereIn('id', $ids)->delete();

            return json_success(__('admin/mail_log.delete_success_message'));
        } catch (\Exception $e) {
            return json_fail(__('admin/mail_log.delete_failed_message', ['error' => $e->getMessage()]));
        }
    }

    /**
     * 批量操作
     */
    public function batchAction(Request $request): JsonResponse
    {
        $action = $request->input('action');
        $ids    = $request->input('ids', []);

        if (empty($ids)) {
            return json_fail(__('admin/mail_log.select_records_to_operate'));
        }

        try {
            switch ($action) {
                case 'delete':
                    MailLog::whereIn('id', $ids)->delete();

                    return json_success(__('admin/mail_log.batch_delete_success_message'));

                case 'mark_as_sent':
                    MailLog::whereIn('id', $ids)->update([
                        'status'  => MailLog::STATUS_SENT,
                        'sent_at' => now(),
                    ]);

                    return json_success(__('admin/mail_log.batch_mark_sent_success'));

                case 'mark_as_failed':
                    MailLog::whereIn('id', $ids)->update([
                        'status' => MailLog::STATUS_FAILED,
                    ]);

                    return json_success(__('admin/mail_log.batch_mark_failed_success'));

                default:
                    return json_fail(__('admin/mail_log.unsupported_operation'));
            }
        } catch (\Exception $e) {
            return json_fail(__('admin/mail_log.operation_failed_message', ['error' => $e->getMessage()]));
        }
    }

    /**
     * 清理旧记录
     */
    public function cleanup(Request $request): JsonResponse
    {
        $days = $request->input('days', 30);

        if ($days < 1) {
            return json_fail(__('admin/mail_log.days_must_greater_than_zero'));
        }

        try {
            $cutoffDate   = now()->subDays($days);
            $deletedCount = MailLog::where('created_at', '<', $cutoffDate)->delete();

            return json_success(__('admin/mail_log.cleanup_success_message', ['count' => $deletedCount]));
        } catch (\Exception $e) {
            return json_fail(__('admin/mail_log.cleanup_failed_message', ['error' => $e->getMessage()]));
        }
    }

    /**
     * 获取统计数据
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $days      = $request->input('days', 7);
            $startDate = now()->subDays($days)->startOfDay();

            // 按日期统计
            $dailyStats = MailLog::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
                ->groupBy('date', 'status')
                ->orderBy('date')
                ->get()
                ->groupBy('date');

            // 按邮件类型统计
            $typeStats = MailLog::where('created_at', '>=', $startDate)
                ->selectRaw('mail_type, COUNT(*) as count')
                ->groupBy('mail_type')
                ->orderBy('count', 'desc')
                ->get();

            // 总体统计
            $totalStats = [
                'total'   => MailLog::count(),
                'sent'    => MailLog::where('status', MailLog::STATUS_SENT)->count(),
                'failed'  => MailLog::where('status', MailLog::STATUS_FAILED)->count(),
                'pending' => MailLog::where('status', MailLog::STATUS_PENDING)->count(),
                'recent'  => MailLog::where('created_at', '>=', $startDate)->count(),
            ];

            return json_success(__('admin/mail_log.get_statistics_success'), [
                'daily_stats' => $dailyStats,
                'type_stats'  => $typeStats,
                'total_stats' => $totalStats,
            ]);
        } catch (\Exception $e) {
            return json_fail(__('admin/mail_log.get_statistics_failed', ['error' => $e->getMessage()]));
        }
    }

    /**
     * 重新发送邮件
     */
    public function resend(MailLog $mailLog): JsonResponse
    {
        if ($mailLog->status === MailLog::STATUS_SENT) {
            return json_fail(__('admin/mail_log.mail_already_sent'));
        }

        try {
            // 这里可以实现重新发送邮件的逻辑
            // 由于需要重新构造邮件对象，这个功能比较复杂，暂时返回提示
            return json_fail(__('admin/mail_log.resend_not_implemented_message'));
        } catch (\Exception $e) {
            return json_fail(__('admin/mail_log.resend_failed_message', ['error' => $e->getMessage()]));
        }
    }
}
