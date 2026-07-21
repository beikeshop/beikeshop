<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Admin\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * 获取基础统计数据
     */
    public function getStats(Request $request): JsonResponse
    {
        $timeRange = $request->get('time_range', 'today');

        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        try {
            $stats = $this->dashboardService->getBasicStats($timeRange);

            return response()->json([
                'success' => true,
                'data'    => $stats,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_stats_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 获取访客趋势数据
     */
    public function getTrends(Request $request): JsonResponse
    {
        $timeRange = $request->get('time_range', 'today');

        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        try {
            $trends = $this->dashboardService->getTrendData($timeRange);

            return response()->json([
                'success' => true,
                'data'    => $trends,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_trends_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 获取小型趋势图数据
     */
    public function getMiniChartData(Request $request): JsonResponse
    {
        $timeRange = $request->get('time_range', 'today');

        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        try {
            $miniChartData = $this->dashboardService->getMiniChartData($timeRange);

            return response()->json([
                'success' => true,
                'data'    => $miniChartData,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_mini_chart_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 获取客户来源分析
     */
    public function getSourceAnalysis(Request $request): JsonResponse
    {
        $timeRange = $request->get('time_range', 'today');

        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        try {
            $sourceData = $this->dashboardService->getSourceAnalysis($timeRange);

            return response()->json([
                'success' => true,
                'data'    => $sourceData,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_source_analysis_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 获取转化漏斗数据
     */
    public function getFunnelData(Request $request): JsonResponse
    {
        $timeRange = $request->get('time_range', 'today');

        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        try {
            $funnelData = $this->dashboardService->getFunnelData($timeRange);

            return response()->json([
                'success' => true,
                'data'    => $funnelData,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_funnel_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 获取商品销售排行
     */
    public function getProductRanking(Request $request, $type): JsonResponse
    {
        // $type      = $request->get('type', 'hot'); // hot 或 slow
        $timeRange = $request->get('time_range', 'today');

        // 验证参数
        $validTypes = ['hot', 'slow'];
        if (! in_array($type, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_product_type', ['type' => $type, 'validTypes' => implode(', ', $validTypes)]),
            ], 400);
        }

        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        try {
            $productData = $this->dashboardService->getProductRanking($type, $timeRange);

            return response()->json([
                'success' => true,
                'data'    => $productData,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_product_ranking_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 导出数据报表
     */
    public function exportReport(Request $request)
    {
        // 权限检查
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.please_login_first'),
            ], 401);
        }

        // 检查导出权限（可以根据实际需求调整权限检查逻辑）
        $user = Auth::user();
        if (! $this->hasExportPermission($user)) {
            Log::warning('用户尝试导出报表但无权限', [
                'user_id'    => $user->id,
                'user_email' => $user->email ?? 'unknown',
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.no_export_permission'),
            ], 403);
        }

        $timeRange = $request->get('time_range', 'today');
        $format    = $request->get('format', 'excel');

        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]),
            ], 400);
        }

        $validFormats = ['excel', 'csv'];
        if (! in_array($format, $validFormats)) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.invalid_export_format', ['format' => $format, 'validFormats' => implode(', ', $validFormats)]),
            ], 400);
        }

        // 检查导出频率限制
        //        if (!$this->checkExportRateLimit($user)) {
        //            return response()->json([
        //                'success' => false,
        //                'message' => __('admin/dashboard.export_rate_limit'),
        //            ], 429);
        //        }

        try {
            // 记录导出日志
            Log::info('用户导出数据看板报表', [
                'user_id'    => $user->id,
                'user_email' => $user->email ?? 'unknown',
                'time_range' => $timeRange,
                'format'     => $format,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp'  => now()->toDateTimeString(),
            ]);

            return $this->dashboardService->exportReport($timeRange, $format);
        } catch (\InvalidArgumentException $e) {
            Log::error('导出报表参数错误', [
                'user_id'    => $user->id,
                'error'      => $e->getMessage(),
                'time_range' => $timeRange,
                'format'     => $format,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            Log::error('导出报表失败', [
                'user_id'    => $user->id,
                'error'      => $e->getMessage(),
                'time_range' => $timeRange,
                'format'     => $format,
                'trace'      => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.export_report_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 检查用户是否有导出权限
     */
    private function hasExportPermission($user): bool
    {
        // 移除权限验证，允许所有已登录用户导出报表
        return true;
    }

    /**
     * 检查导出频率限制
     */
    private function checkExportRateLimit($user): bool
    {
        // 使用缓存记录用户的导出频率
        $cacheKey    = "dashboard_export_limit_{$user->id}";
        $exportCount = cache()->get($cacheKey, 0);

        // 限制每小时最多导出10次
        $maxExportsPerHour = 10;

        if ($exportCount >= $maxExportsPerHour) {
            return false;
        }

        // 增加导出计数
        cache()->put($cacheKey, $exportCount + 1, 3600); // 1小时过期

        return true;
    }

    /**
     * 获取缓存状态
     */
    public function getCacheStatus(): JsonResponse
    {
        try {
            $status = $this->dashboardService->getCacheStatus();

            return response()->json([
                'success' => true,
                'data'    => $status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.get_cache_status_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * 清理缓存
     */
    public function clearCache(): JsonResponse
    {
        try {
            $this->dashboardService->clearDashboardCache();

            return response()->json([
                'success' => true,
                'message' => __('admin/dashboard.cache_clear_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin/dashboard.cache_clear_failed', ['message' => $e->getMessage()]),
            ], 500);
        }
    }
}
