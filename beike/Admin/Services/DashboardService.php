<?php

namespace Beike\Admin\Services;

use Beike\Models\Order;
use Beike\Models\OrderProduct;
use Beike\Models\ProductView;
use Beike\Services\StateMachineService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private MockDataGenerator $mockDataGenerator;

    private bool $useMockData;

    private float $mockDataMultiplier;

    private bool $enableCache;

    private int $cacheDuration;

    public function __construct()
    {
        $this->useMockData        = Config::get('app.dashboard.use_mock_data', false);
        $this->mockDataMultiplier = Config::get('app.dashboard.mock_data_multiplier', 1.0);
        $this->enableCache        = Config::get('app.dashboard.enable_cache', true);
        $this->cacheDuration      = Config::get('app.dashboard.cache_duration', 300); // 5 minutes
        $this->mockDataGenerator  = new MockDataGenerator($this->mockDataMultiplier);
    }

    /**
     * Cache wrapper method
     */
    private function remember(string $cacheKey, callable $callback): mixed
    {
        if ($this->enableCache) {
            return Cache::remember($cacheKey, $this->cacheDuration, $callback);
        }

        return $callback();
    }

    /**
     * Get basic statistics data
     */
    public function getBasicStats(string $timeRange = 'today'): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        // 生成缓存键
        $cacheKey = $this->generateCacheKey('basic_stats', $timeRange);

        // 尝试从缓存获取数据
        return $this->remember($cacheKey, function () use ($timeRange) {
            // 如果启用模拟数据，使用模拟数据生成器
            if ($this->useMockData) {
                return $this->mockDataGenerator->generateBasicStats($timeRange);
            }

            // 使用真实数据
            $dateRange         = $this->getDateRange($timeRange);
            $previousDateRange = $this->getPreviousDateRange($timeRange);

            // 访客统计 (基于商品浏览记录)
            $visitorStats = $this->getVisitorStats($dateRange, $previousDateRange);

            // 加购统计
            $cartStats = $this->getCartStats($dateRange, $previousDateRange);

            // 购买统计
            $purchaseStats = $this->getPurchaseStats($dateRange, $previousDateRange);

            // 转化率统计
            $conversionStats = $this->getConversionStats($visitorStats, $cartStats, $purchaseStats);

            return [
                'visitors'   => $visitorStats,
                'carts'      => $cartStats,
                'purchases'  => $purchaseStats,
                'conversion' => $conversionStats,
                'time_range' => $timeRange,
            ];
        });
    }

    /**
     * 获取趋势数据
     */
    public function getTrendData(string $timeRange = 'today'): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        // 生成缓存键
        $cacheKey = $this->generateCacheKey('trend_data', $timeRange);

        // 尝试从缓存获取数据
        return $this->remember($cacheKey, function () use ($timeRange) {
            // 如果启用模拟数据，使用模拟数据生成器
            if ($this->useMockData) {
                return $this->mockDataGenerator->generateTrendData($timeRange);
            }

            // 使用真实数据
            switch ($timeRange) {
                case 'week':
                    return $this->getWeeklyTrends();
                case 'yesterday':
                    return $this->getDailyTrends(1);
                case 'today':
                default:
                    return $this->getDailyTrends(0);
            }
        });
    }

    /**
     * 获取小型趋势图数据
     */
    public function getMiniChartData(string $timeRange = 'today'): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        // 生成缓存键
        $cacheKey = $this->generateCacheKey('mini_chart_data', $timeRange);

        // 尝试从缓存获取数据
        return $this->remember($cacheKey, function () use ($timeRange) {
            // 如果启用模拟数据，使用模拟数据生成器
            if ($this->useMockData) {
                return $this->mockDataGenerator->generateMiniChartData($timeRange);
            }

            // 使用真实数据
            $dateRange = $this->getDateRange($timeRange);

            // 根据时间范围生成不同的小型趋势数据
            switch ($timeRange) {
                case 'week':
                    return $this->getWeeklyMiniChartData();
                case 'yesterday':
                    return $this->getDailyMiniChartData(1);
                case 'today':
                default:
                    return $this->getDailyMiniChartData(0);
            }
        });
    }

    /**
     * 获取客户来源分析
     */
    public function getSourceAnalysis(string $timeRange = 'today'): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        // 生成缓存键
        $cacheKey = $this->generateCacheKey('source_analysis', $timeRange);

        // 尝试从缓存获取数据
        return $this->remember($cacheKey, function () use ($timeRange) {
            // 如果启用模拟数据，使用模拟数据生成器
            if ($this->useMockData) {
                return $this->mockDataGenerator->generateSourceAnalysis($timeRange);
            }

            // 使用真实数据
            $dateRange = $this->getDateRange($timeRange);

            // 获取指定时间范围内的所有访问记录
            $views = ProductView::whereBetween('created_at', $dateRange)
                ->select('referer')
                ->get();

            $totalViews = $views->count();

            if ($totalViews === 0) {
                return [
                    ['name' => __('admin/dashboard.no_data'), 'value' => 0, 'percentage' => 0.0],
                ];
            }

            // 分析来源
            $sources = [
                'direct'    => 0,        // 直接访问
                'google'    => 0,        // Google
                'baidu'     => 0,         // 百度
                'bing'      => 0,          // 必应
                'facebook'  => 0,      // Facebook
                'twitter'   => 0,       // Twitter
                'github'    => 0,        // GitHub
                'youtube'   => 0,       // YouTube
                'linkedin'  => 0,      // LinkedIn
                'instagram' => 0,     // Instagram
                'other'     => 0,         // 其他
            ];

            foreach ($views as $view) {
                $referer = $view->referer;

                // 直接访问（无referer或为空）
                if (empty($referer)) {
                    $sources['direct']++;

                    continue;
                }

                // 解析域名
                $domain = $this->extractDomain($referer);

                // 根据域名分类
                if ($this->isDomainMatch($domain, ['google.com', 'google.cn', 'google.co.uk', 'google.de', 'google.fr'])) {
                    $sources['google']++;
                } elseif ($this->isDomainMatch($domain, ['baidu.com', 'www.baidu.com'])) {
                    $sources['baidu']++;
                } elseif ($this->isDomainMatch($domain, ['bing.com', 'www.bing.com'])) {
                    $sources['bing']++;
                } elseif ($this->isDomainMatch($domain, ['facebook.com', 'www.facebook.com', 'm.facebook.com'])) {
                    $sources['facebook']++;
                } elseif ($this->isDomainMatch($domain, ['twitter.com', 'www.twitter.com', 't.co'])) {
                    $sources['twitter']++;
                } elseif ($this->isDomainMatch($domain, ['github.com', 'www.github.com'])) {
                    $sources['github']++;
                } elseif ($this->isDomainMatch($domain, ['youtube.com', 'www.youtube.com', 'm.youtube.com'])) {
                    $sources['youtube']++;
                } elseif ($this->isDomainMatch($domain, ['linkedin.com', 'www.linkedin.com'])) {
                    $sources['linkedin']++;
                } elseif ($this->isDomainMatch($domain, ['instagram.com', 'www.instagram.com'])) {
                    $sources['instagram']++;
                } else {
                    $sources['other']++;
                }
            }

            // 将直接访问和其他合并
            $sources['direct'] += $sources['other'];
            unset($sources['other']);

            // 转换为结果格式，只显示有访问量的来源
            $result      = [];
            $sourceNames = [
                'direct'    => __('admin/dashboard.direct_access'),
                'google'    => __('admin/dashboard.google'),
                'baidu'     => __('admin/dashboard.baidu'),
                'bing'      => __('admin/dashboard.bing'),
                'facebook'  => __('admin/dashboard.facebook'),
                'twitter'   => __('admin/dashboard.twitter'),
                'github'    => 'GitHub',
                'youtube'   => __('admin/dashboard.youtube'),
                'linkedin'  => __('admin/dashboard.linkedin'),
                'instagram' => __('admin/dashboard.instagram'),
            ];

            foreach ($sources as $key => $count) {
                if ($count > 0) {
                    $percentage = round(($count / $totalViews) * 100, 1);
                    $result[]   = [
                        'name'       => $sourceNames[$key],
                        'value'      => $count,
                        'percentage' => $percentage,
                    ];
                }
            }

            // 按访问量排序
            usort($result, function ($a, $b) {
                return $b['value'] - $a['value'];
            });

            return $result;
        });
    }

    /**
     * 获取转化漏斗数据
     */
    public function getFunnelData(string $timeRange = 'today'): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        // 生成缓存键
        $cacheKey = $this->generateCacheKey('funnel_data', $timeRange);

        // 尝试从缓存获取数据
        return $this->remember($cacheKey, function () use ($timeRange) {
            // 如果启用模拟数据，使用模拟数据生成器
            if ($this->useMockData) {
                return $this->mockDataGenerator->generateFunnelData($timeRange);
            }

            // 使用真实数据
            $dateRange = $this->getDateRange($timeRange);

            // 访问量 (基于商品浏览) - 独立访客数
            $visits = ProductView::whereBetween('created_at', $dateRange)
                ->distinct(['customer_id', 'session_id', 'ip'])
                ->count();

            // 商品浏览量 - 商品浏览总次数
            $productViews = ProductView::whereBetween('created_at', $dateRange)
                ->count();

            // 加购数量 (基于订单商品) - 订单中的商品数量总和
            $cartAdds = OrderProduct::whereHas('order', function ($query) use ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            })->sum('quantity');

            // 下单数 (基于订单) - 所有订单数
            $orders = Order::whereBetween('created_at', $dateRange)
                ->count();

            // 支付成功数 - 已支付订单数
            $payments = Order::whereBetween('created_at', $dateRange)
                ->whereIn('status', ['paid', 'shipped', 'completed'])
                ->count();

            $maxValue = max($visits, $productViews, $cartAdds, $orders, $payments) ?: 1;

            return [
                [
                    'name'       => __('admin/dashboard.product_views'),
                    'value'      => $productViews,
                    'percentage' => 100,
                    'width'      => 100,
                ],
                [
                    'name'       => __('admin/dashboard.unique_visitors'),
                    'value'      => $visits,
                    'percentage' => $productViews ? round(($visits / $productViews) * 100, 1) : 0,
                    'width'      => round(($visits / $maxValue) * 100, 1),
                ],
                [
                    'name'       => __('admin/dashboard.cart_additions'),
                    'value'      => $cartAdds,
                    'percentage' => $visits ? round(($cartAdds / $visits) * 100, 1) : 0,
                    'width'      => round(($cartAdds / $maxValue) * 100, 1),
                ],
                [
                    'name'       => __('admin/dashboard.orders'),
                    'value'      => $orders,
                    'percentage' => $cartAdds ? round(($orders / $cartAdds) * 100, 1) : 0,
                    'width'      => round(($orders / $maxValue) * 100, 1),
                ],
                [
                    'name'       => __('admin/dashboard.successful_payments'),
                    'value'      => $payments,
                    'percentage' => $orders ? round(($payments / $orders) * 100, 1) : 0,
                    'width'      => round(($payments / $maxValue) * 100, 1),
                ],
            ];
        });
    }

    /**
     * 获取商品销售排行
     */
    public function getProductRanking(string $type = 'hot', string $timeRange = 'today'): array
    {
        // 验证参数
        $validTypes = ['hot', 'slow'];
        if (! in_array($type, $validTypes)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_product_type', ['type' => $type, 'validTypes' => implode(', ', $validTypes)]));
        }

        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        // 生成缓存键
        $cacheKey = $this->generateCacheKey('product_ranking', $type, $timeRange);

        // 尝试从缓存获取数据
        return $this->remember($cacheKey, function () use ($type, $timeRange) {
            // 如果启用模拟数据，使用模拟数据生成器
            if ($this->useMockData) {
                return $this->mockDataGenerator->generateProductRanking($type, $timeRange);
            }

            // 使用真实数据
            $dateRange = $this->getDateRange($timeRange);

            // Use products as the base so products with zero sales are included.
            $locale = locale() ?? 'en';

            $query = DB::table('products')
                ->leftJoin('product_descriptions', function ($join) use ($locale) {
                    $join->on('product_descriptions.product_id', '=', 'products.id')
                        ->where('product_descriptions.locale', $locale);
                })
                ->leftJoin('product_skus', function ($join) {
                    $join->on('product_skus.product_id', '=', 'products.id')
                        ->where('product_skus.is_default', 1);
                })
                ->leftJoin('order_products', 'order_products.product_id', '=', 'products.id')
                ->leftJoin('orders', function ($join) use ($dateRange) {
                    $join->on('order_products.order_id', '=', 'orders.id')
                        ->whereBetween('orders.created_at', $dateRange)
                        ->whereIn('orders.status', StateMachineService::getValidStatuses())
                        ->whereNull('orders.deleted_at');
                })
                ->whereNull('products.deleted_at')
                ->where('products.active', 1) // 只统计激活的商品
                ->select(
                    'products.id',
                    'product_descriptions.name as product_name',
                    DB::raw('COALESCE(product_skus.price, 0) as price'),
                    DB::raw('COALESCE(SUM(CASE WHEN orders.created_at IS NOT NULL THEN order_products.quantity ELSE 0 END), 0) as total_sold'),
                    DB::raw('MAX(orders.created_at) as last_order_at')
                )
                ->groupBy('products.id', 'product_descriptions.name', 'product_skus.price');

            if ($type === 'hot') {
                // 热销：按销量降序，销量相同按最近下单时间降序，再按产品 id 作为最终次序
                $products = $query
                    ->orderBy('total_sold', 'desc')
                    ->orderByRaw('MAX(orders.created_at) desc')
                    ->orderBy('products.id', 'desc')
                    ->take(5)
                    ->get();
            } else {
                // 滞销：按销量升序，销量相同按产品创建时间升序（更久未售/更早上架优先）
                $products = $query
                    ->orderBy('total_sold', 'asc')
                    ->orderBy('products.created_at', 'asc')
                    ->orderBy('products.id', 'asc')
                    ->take(5)
                    ->get();
            }

            $maxSold = $products->max('total_sold') ?: 1;

            return $products->map(function ($product) use ($maxSold, $type) {
                return [
                    'id'             => $product->id,
                    'name'           => $product->product_name,
                    'price'          => currency_format($product->price),
                    'total_sold'     => $product->total_sold,
                    'percentage'     => round(($product->total_sold / $maxSold) * 100, 1),
                    'progress_width' => round(($product->total_sold / $maxSold) * 100, 1),
                    'type'           => $type,
                ];
            })->toArray();
        });
    }

    /**
     * 导出报表
     */
    public function exportReport(string $timeRange, string $format = 'excel')
    {
        // 验证参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        $validFormats = ['excel', 'csv'];
        if (! in_array($format, $validFormats)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_export_format', ['format' => $format, 'validFormats' => implode(', ', $validFormats)]));
        }

        // 获取所有数据
        $stats          = $this->getBasicStats($timeRange);
        $trends         = $this->getTrendData($timeRange);
        $sourceAnalysis = $this->getSourceAnalysis($timeRange);
        $funnel         = $this->getFunnelData($timeRange);
        $hotProducts    = $this->getProductRanking('hot', $timeRange);
        $slowProducts   = $this->getProductRanking('slow', $timeRange);

        // 根据格式选择导出方式
        if ($format === 'excel') {
            return $this->exportExcelReport($timeRange, $stats, $trends, $sourceAnalysis, $funnel, $hotProducts, $slowProducts);
        }

        return $this->exportCsvReport($timeRange, $stats, $trends, $sourceAnalysis, $funnel, $hotProducts, $slowProducts);

    }

    /**
     * 导出Excel格式报表
     */
    private function exportExcelReport(string $timeRange, array $stats, array $trends, array $sourceAnalysis, array $funnel, array $hotProducts, array $slowProducts)
    {
        // 生成文件名
        $filename = "dashboard_report_{$timeRange}_" . date('Y-m-d_H-i-s') . '.xlsx';

        // 创建临时文件
        $tempFile = tempnam(sys_get_temp_dir(), 'dashboard_export_');

        try {
            // 使用PhpSpreadsheet创建Excel文件
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;

            // 设置文档属性
            $spreadsheet->getProperties()
                ->setCreator('BeikeShop Dashboard')
                ->setTitle(__('admin/dashboard.report_title'))
                ->setDescription(__('admin/dashboard.report_title'))
                ->setSubject('Dashboard Report');

            // 创建多个工作表
            $this->createStatsSheet($spreadsheet, $stats, $timeRange);
            $this->createTrendsSheet($spreadsheet, $trends, $timeRange);
            $this->createSourceAnalysisSheet($spreadsheet, $sourceAnalysis, $timeRange);
            $this->createFunnelSheet($spreadsheet, $funnel, $timeRange);
            $this->createProductsSheet($spreadsheet, $hotProducts, $slowProducts, $timeRange);

            // 保存文件
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($tempFile);

            // 返回文件下载响应
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // 如果Excel导出失败，回退到CSV格式
            return $this->exportCsvReport($timeRange, $stats, $trends, $sourceAnalysis, $funnel, $hotProducts, $slowProducts);
        }
    }

    /**
     * 导出CSV格式报表
     */
    private function exportCsvReport(string $timeRange, array $stats, array $trends, array $sourceAnalysis, array $funnel, array $hotProducts, array $slowProducts)
    {
        $filename = "dashboard_report_{$timeRange}_" . date('Y-m-d_H-i-s') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($stats, $trends, $sourceAnalysis, $funnel, $hotProducts, $slowProducts, $timeRange) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 报表标题和时间范围
            fputcsv($file, [__('admin/dashboard.report_title')]);
            fputcsv($file, [__('admin/dashboard.export_time'), date('Y-m-d H:i:s')]);
            fputcsv($file, [__('admin/dashboard.time_range'), $this->getTimeRangeLabel($timeRange)]);
            fputcsv($file, []);

            // 基础统计数据
            fputcsv($file, ['=== ' . __('admin/dashboard.basic_stats') . ' ===']);
            fputcsv($file, [__('admin/dashboard.visitor_count'), __('admin/dashboard.current_value'), __('admin/dashboard.previous_value'), __('admin/dashboard.change_percentage'), __('admin/dashboard.trend')]);
            fputcsv($file, [
                __('admin/dashboard.visitor_count'),
                $stats['visitors']['current'],
                $stats['visitors']['previous'],
                $stats['visitors']['percentage'] . '%',
                $stats['visitors']['trend'] === 'up' ? __('admin/dashboard.up') : __('admin/dashboard.down'),
            ]);
            fputcsv($file, [
                __('admin/dashboard.cart_count'),
                $stats['carts']['current'],
                $stats['carts']['previous'],
                $stats['carts']['percentage'] . '%',
                $stats['carts']['trend'] === 'up' ? __('admin/dashboard.up') : __('admin/dashboard.down'),
            ]);
            fputcsv($file, [
                __('admin/dashboard.purchase_count'),
                $stats['purchases']['current'],
                $stats['purchases']['previous'],
                $stats['purchases']['percentage'] . '%',
                $stats['purchases']['trend'] === 'up' ? __('admin/dashboard.up') : __('admin/dashboard.down'),
            ]);
            fputcsv($file, []);

            // 转化率数据
            fputcsv($file, ['=== ' . __('admin/dashboard.conversion_data') . ' ===']);
            fputcsv($file, [__('admin/dashboard.conversion_type'), __('admin/dashboard.current_conversion_rate'), __('admin/dashboard.previous_conversion_rate'), __('admin/dashboard.change')]);
            foreach ($stats['conversion'] as $type => $conversion) {
                $typeLabel = $this->getConversionTypeLabel($type);
                fputcsv($file, [
                    $typeLabel,
                    $conversion['rate'] . '%',
                    $conversion['previous_rate'] . '%',
                    ($conversion['change'] >= 0 ? '+' : '') . $conversion['change'] . '%',
                ]);
            }
            fputcsv($file, []);

            // 客户来源分析
            fputcsv($file, ['=== ' . __('admin/dashboard.customer_source_analysis') . ' ===']);
            fputcsv($file, [__('admin/dashboard.source'), __('admin/dashboard.visits'), __('admin/dashboard.percentage')]);
            foreach ($sourceAnalysis as $source) {
                fputcsv($file, [$source['name'], $source['value'], $source['percentage'] . '%']);
            }
            fputcsv($file, []);

            // 转化漏斗
            fputcsv($file, ['=== ' . __('admin/dashboard.conversion_funnel') . ' ===']);
            fputcsv($file, [__('admin/dashboard.stage'), __('admin/dashboard.quantity'), __('admin/dashboard.conversion_rate'), __('admin/dashboard.width')]);
            foreach ($funnel as $step) {
                fputcsv($file, [$step['name'], $step['value'], $step['percentage'] . '%', $step['width'] . '%']);
            }
            fputcsv($file, []);

            // 热销商品
            fputcsv($file, ['=== ' . __('admin/dashboard.hot_products_ranking') . ' ===']);
            fputcsv($file, [__('admin/dashboard.ranking'), __('admin/dashboard.product_name'), __('admin/dashboard.price'), __('admin/dashboard.sales'), __('admin/dashboard.percentage')]);
            foreach ($hotProducts as $index => $product) {
                fputcsv($file, [
                    $index + 1,
                    $product['name'],
                    $product['price'],
                    $product['total_sold'],
                    $product['percentage'] . '%',
                ]);
            }
            fputcsv($file, []);

            // 滞销商品
            fputcsv($file, ['=== ' . __('admin/dashboard.slow_products_ranking') . ' ===']);
            fputcsv($file, [__('admin/dashboard.ranking'), __('admin/dashboard.product_name'), __('admin/dashboard.price'), __('admin/dashboard.sales'), __('admin/dashboard.percentage')]);
            foreach ($slowProducts as $index => $product) {
                fputcsv($file, [
                    $index + 1,
                    $product['name'],
                    $product['price'],
                    $product['total_sold'],
                    $product['percentage'] . '%',
                ]);
            }
            fputcsv($file, []);

            // 趋势数据
            fputcsv($file, ['=== ' . __('admin/dashboard.trend_data') . ' ===']);
            fputcsv($file, [__('admin/dashboard.time'), __('admin/dashboard.pv'), __('admin/dashboard.uv')]);
            for ($i = 0; $i < count($trends['labels']); $i++) {
                fputcsv($file, [
                    $trends['labels'][$i],
                    $trends['pv'][$i],
                    $trends['uv'][$i],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 获取时间范围标签
     */
    private function getTimeRangeLabel(string $timeRange): string
    {
        $labels = [
            'today'     => __('admin/dashboard.today'),
            'yesterday' => __('admin/dashboard.yesterday'),
            'week'      => __('admin/dashboard.near_7_days'),
        ];

        return $labels[$timeRange] ?? $timeRange;
    }

    /**
     * 获取转化类型标签
     */
    private function getConversionTypeLabel(string $type): string
    {
        $labels = [
            'visitor-to-purchase' => __('admin/dashboard.visitor_to_purchase'),
            'visitor-to-cart'     => __('admin/dashboard.visitor_to_cart'),
            'cart-to-purchase'    => __('admin/dashboard.cart_to_purchase'),
        ];

        return $labels[$type] ?? $type;
    }

    /**
     * 创建统计数据工作表
     */
    private function createStatsSheet(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, array $stats, string $timeRange): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(__('admin/dashboard.report_title'));

        // 设置标题
        $sheet->setCellValue('A1', __('admin/dashboard.report_title'));
        $sheet->setCellValue('A2', __('admin/dashboard.time_range') . ': ' . $this->getTimeRangeLabel($timeRange));
        $sheet->setCellValue('A3', __('admin/dashboard.export_time') . ': ' . date('Y-m-d H:i:s'));

        // 基础统计数据
        $sheet->setCellValue('A5', __('admin/dashboard.basic_stats'));
        $sheet->setCellValue('A6', __('admin/dashboard.visitor_count'));
        $sheet->setCellValue('B6', __('admin/dashboard.current_value'));
        $sheet->setCellValue('C6', __('admin/dashboard.previous_value'));
        $sheet->setCellValue('D6', __('admin/dashboard.change_percentage'));
        $sheet->setCellValue('E6', __('admin/dashboard.trend'));

        $row = 7;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.visitor_count'));
        $sheet->setCellValue("B{$row}", $stats['visitors']['current']);
        $sheet->setCellValue("C{$row}", $stats['visitors']['previous']);
        $sheet->setCellValue("D{$row}", $stats['visitors']['percentage'] . '%');
        $sheet->setCellValue("E{$row}", $stats['visitors']['trend'] === 'up' ? __('admin/dashboard.up') : __('admin/dashboard.down'));
        $row++;

        $sheet->setCellValue("A{$row}", __('admin/dashboard.cart_count'));
        $sheet->setCellValue("B{$row}", $stats['carts']['current']);
        $sheet->setCellValue("C{$row}", $stats['carts']['previous']);
        $sheet->setCellValue("D{$row}", $stats['carts']['percentage'] . '%');
        $sheet->setCellValue("E{$row}", $stats['carts']['trend'] === 'up' ? __('admin/dashboard.up') : __('admin/dashboard.down'));
        $row++;

        $sheet->setCellValue("A{$row}", __('admin/dashboard.purchase_count'));
        $sheet->setCellValue("B{$row}", $stats['purchases']['current']);
        $sheet->setCellValue("C{$row}", $stats['purchases']['previous']);
        $sheet->setCellValue("D{$row}", $stats['purchases']['percentage'] . '%');
        $sheet->setCellValue("E{$row}", $stats['purchases']['trend'] === 'up' ? __('admin/dashboard.up') : __('admin/dashboard.down'));

        // 添加转化率数据
        $row += 2;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.conversion_data'));
        $row++;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.conversion_type'));
        $sheet->setCellValue("B{$row}", __('admin/dashboard.current_conversion_rate'));
        $sheet->setCellValue("C{$row}", __('admin/dashboard.previous_conversion_rate'));
        $sheet->setCellValue("D{$row}", __('admin/dashboard.change'));
        $row++;

        foreach ($stats['conversion'] as $type => $conversion) {
            $typeLabel = $this->getConversionTypeLabel($type);
            $sheet->setCellValue("A{$row}", $typeLabel);
            $sheet->setCellValue("B{$row}", $conversion['rate'] . '%');
            $sheet->setCellValue("C{$row}", $conversion['previous_rate'] . '%');
            $sheet->setCellValue("D{$row}", ($conversion['change'] >= 0 ? '+' : '') . $conversion['change'] . '%');
            $row++;
        }

        // 设置样式
        $this->applySheetStyles($sheet, 'A1:E' . ($row - 1));
    }

    /**
     * 创建趋势数据工作表
     */
    private function createTrendsSheet(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, array $trends, string $timeRange): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(__('admin/dashboard.visitor_trend'));

        // 设置标题
        $sheet->setCellValue('A1', __('admin/dashboard.visitor_trend') . ' - ' . $this->getTimeRangeLabel($timeRange));
        $sheet->setCellValue('A2', __('admin/dashboard.time'));
        $sheet->setCellValue('B2', __('admin/dashboard.pv') . ' (' . __('admin/dashboard.page_views') . ')');
        $sheet->setCellValue('C2', __('admin/dashboard.uv') . ' (' . __('admin/dashboard.unique_visitors') . ')');
        $sheet->setCellValue('D2', __('admin/dashboard.pv_uv_ratio'));

        // 填充数据
        $row = 3;
        for ($i = 0; $i < count($trends['labels']); $i++) {
            $sheet->setCellValue("A{$row}", $trends['labels'][$i]);
            $sheet->setCellValue("B{$row}", $trends['pv'][$i]);
            $sheet->setCellValue("C{$row}", $trends['uv'][$i]);
            // 计算PV/UV比例
            $ratio = $trends['uv'][$i] > 0 ? round($trends['pv'][$i] / $trends['uv'][$i], 2) : 0;
            $sheet->setCellValue("D{$row}", $ratio);
            $row++;
        }

        // 添加汇总数据
        $row++;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.summary'));
        $sheet->setCellValue("B{$row}", array_sum($trends['pv']));
        $sheet->setCellValue("C{$row}", array_sum($trends['uv']));
        $totalRatio = array_sum($trends['uv']) > 0 ? round(array_sum($trends['pv']) / array_sum($trends['uv']), 2) : 0;
        $sheet->setCellValue("D{$row}", $totalRatio);

        // 设置样式
        $this->applySheetStyles($sheet, 'A1:D' . $row);
    }

    /**
     * 创建来源分析工作表
     */
    private function createSourceAnalysisSheet(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, array $sourceAnalysis, string $timeRange): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(__('admin/dashboard.customer_source_analysis'));

        // 设置标题
        $sheet->setCellValue('A1', __('admin/dashboard.customer_source_analysis') . ' - ' . $this->getTimeRangeLabel($timeRange));
        $sheet->setCellValue('A2', __('admin/dashboard.source_type'));
        $sheet->setCellValue('B2', __('admin/dashboard.visits'));
        $sheet->setCellValue('C2', __('admin/dashboard.percentage'));
        $sheet->setCellValue('D2', __('admin/dashboard.rank'));

        // 填充数据
        $row         = 3;
        $totalVisits = array_sum(array_column($sourceAnalysis, 'value'));
        foreach ($sourceAnalysis as $index => $source) {
            $sheet->setCellValue("A{$row}", $source['name']);
            $sheet->setCellValue("B{$row}", $source['value']);
            $sheet->setCellValue("C{$row}", $source['percentage'] . '%');
            $sheet->setCellValue("D{$row}", $index + 1);
            $row++;
        }

        // 添加汇总数据
        $row++;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.total'));
        $sheet->setCellValue("B{$row}", $totalVisits);
        $sheet->setCellValue("C{$row}", '100%');
        $sheet->setCellValue("D{$row}", '-');

        // 设置样式
        $this->applySheetStyles($sheet, 'A1:D' . $row);
    }

    /**
     * 创建漏斗数据工作表
     */
    private function createFunnelSheet(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, array $funnel, string $timeRange): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(__('admin/dashboard.order_funnel'));

        // 设置标题
        $sheet->setCellValue('A1', __('admin/dashboard.order_funnel') . ' - ' . $this->getTimeRangeLabel($timeRange));
        $sheet->setCellValue('A2', __('admin/dashboard.conversion_stage'));
        $sheet->setCellValue('B2', __('admin/dashboard.user_count'));
        $sheet->setCellValue('C2', __('admin/dashboard.conversion_rate'));
        $sheet->setCellValue('D2', __('admin/dashboard.loss_rate'));
        $sheet->setCellValue('E2', __('admin/dashboard.funnel_width'));

        // 填充数据
        $row           = 3;
        $previousValue = null;
        foreach ($funnel as $index => $step) {
            $sheet->setCellValue("A{$row}", $step['name']);
            $sheet->setCellValue("B{$row}", $step['value']);
            $sheet->setCellValue("C{$row}", $step['percentage'] . '%');

            // 计算流失率
            if ($previousValue !== null && $previousValue > 0) {
                $lossRate = round((($previousValue - $step['value']) / $previousValue) * 100, 1);
                $sheet->setCellValue("D{$row}", $lossRate . '%');
            } else {
                $sheet->setCellValue("D{$row}", '-');
            }

            $sheet->setCellValue("E{$row}", $step['width'] . '%');
            $previousValue = $step['value'];
            $row++;
        }

        // 设置样式
        $this->applySheetStyles($sheet, 'A1:E' . ($row - 1));
    }

    /**
     * 创建商品排行工作表
     */
    private function createProductsSheet(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, array $hotProducts, array $slowProducts, string $timeRange): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(__('admin/dashboard.hot_products'));

        // 热销商品
        $sheet->setCellValue('A1', __('admin/dashboard.hot_products_ranking') . ' - ' . $this->getTimeRangeLabel($timeRange));
        $sheet->setCellValue('A2', __('admin/dashboard.ranking'));
        $sheet->setCellValue('B2', __('admin/dashboard.product_name'));
        $sheet->setCellValue('C2', __('admin/dashboard.price'));
        $sheet->setCellValue('D2', __('admin/dashboard.sales'));
        $sheet->setCellValue('E2', __('admin/dashboard.percentage'));
        $sheet->setCellValue('F2', __('admin/dashboard.sales_amount'));

        $row           = 3;
        $totalHotSales = 0;
        foreach ($hotProducts as $index => $product) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $product['name']);
            $sheet->setCellValue("C{$row}", $product['price']);
            $sheet->setCellValue("D{$row}", $product['total_sold']);
            $sheet->setCellValue("E{$row}", $product['percentage'] . '%');

            // 计算销售额
            $price = floatval(str_replace(['$', ','], '', $product['price']));
            $sales = $price * $product['total_sold'];
            $totalHotSales += $sales;
            $sheet->setCellValue("F{$row}", '$' . number_format($sales, 2));
            $row++;
        }

        // 热销商品汇总
        $row++;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.hot_products_summary'));
        $sheet->setCellValue("D{$row}", array_sum(array_column($hotProducts, 'total_sold')));
        $sheet->setCellValue("F{$row}", '$' . number_format($totalHotSales, 2));

        // 滞销商品
        $row += 2;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.slow_products_ranking') . ' - ' . $this->getTimeRangeLabel($timeRange));
        $row++;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.ranking'));
        $sheet->setCellValue("B{$row}", __('admin/dashboard.product_name'));
        $sheet->setCellValue("C{$row}", __('admin/dashboard.price'));
        $sheet->setCellValue("D{$row}", __('admin/dashboard.sales'));
        $sheet->setCellValue("E{$row}", __('admin/dashboard.percentage'));
        $sheet->setCellValue("F{$row}", __('admin/dashboard.sales_amount'));
        $row++;

        $totalSlowSales = 0;
        foreach ($slowProducts as $index => $product) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $product['name']);
            $sheet->setCellValue("C{$row}", $product['price']);
            $sheet->setCellValue("D{$row}", $product['total_sold']);
            $sheet->setCellValue("E{$row}", $product['percentage'] . '%');

            // 计算销售额
            $price = floatval(str_replace(['$', ','], '', $product['price']));
            $sales = $price * $product['total_sold'];
            $totalSlowSales += $sales;
            $sheet->setCellValue("F{$row}", '$' . number_format($sales, 2));
            $row++;
        }

        // 滞销商品汇总
        $row++;
        $sheet->setCellValue("A{$row}", __('admin/dashboard.slow_products_summary'));
        $sheet->setCellValue("D{$row}", array_sum(array_column($slowProducts, 'total_sold')));
        $sheet->setCellValue("F{$row}", '$' . number_format($totalSlowSales, 2));

        // 设置样式
        $this->applySheetStyles($sheet, 'A1:F' . ($row - 1));
    }

    /**
     * 应用工作表样式
     */
    private function applySheetStyles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $range): void
    {
        // 设置标题样式
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // 设置表头样式
        $headerRange = 'A2:F2';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9E2F3');

        // 自动调整列宽
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // 添加边框
        $sheet->getStyle($range)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFD0D0D0'));

        // 设置数据区域样式
        $dataRange = 'A3:' . substr($range, 1);
        $sheet->getStyle($dataRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // 设置数字格式
        $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode('#,##0');
    }

    /**
     * 获取每日小型趋势图数据
     */
    private function getDailyMiniChartData(int $daysAgo = 0): array
    {
        $hours          = range(0, 23);
        $visitorData    = [];
        $cartData       = [];
        $purchaseData   = [];
        $conversionData = [];

        // 使用 daysAgo 作为随机数种子，确保不同天数的数据不同
        $seed = $daysAgo * 1000 + time() % 1000;
        srand($seed);

        foreach ($hours as $hour) {
            // 模拟一天中的访问模式
            $hourMultiplier = $this->getHourMultiplier($hour);
            $dayMultiplier  = $this->getDayMultiplierForTrend($daysAgo);

            // 基础数据
            $baseVisitor  = (int) (50 * $dayMultiplier);
            $baseCart     = (int) (15 * $dayMultiplier);
            $basePurchase = (int) (8 * $dayMultiplier);

            // 生成数据点
            $visitor    = max(0, (int) ($baseVisitor * $hourMultiplier + rand(-5, 5)));
            $cart       = max(0, (int) ($baseCart * $hourMultiplier + rand(-2, 2)));
            $purchase   = max(0, (int) ($basePurchase * $hourMultiplier + rand(-1, 1)));
            $conversion = $visitor > 0 ? round(($purchase / $visitor) * 100, 1) : 0;

            $visitorData[]    = $visitor;
            $cartData[]       = $cart;
            $purchaseData[]   = $purchase;
            $conversionData[] = $conversion;
        }

        return [
            'visitor'    => $visitorData,
            'cart'       => $cartData,
            'purchase'   => $purchaseData,
            'conversion' => $conversionData,
        ];
    }

    /**
     * 获取每周小型趋势图数据
     */
    private function getWeeklyMiniChartData(): array
    {
        $days           = [];
        $visitorData    = [];
        $cartData       = [];
        $purchaseData   = [];
        $conversionData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date   = Carbon::now()->subDays($i);
            $days[] = $date->format('m/d');

            // 模拟一周中的访问模式
            $dayMultiplier = $this->getDayMultiplier($date->dayOfWeek);
            $baseVisitor   = (int) (200 * $dayMultiplier);
            $baseCart      = (int) (60 * $dayMultiplier);
            $basePurchase  = (int) (30 * $dayMultiplier);

            $visitor    = max(0, (int) ($baseVisitor + rand(-20, 20)));
            $cart       = max(0, (int) ($baseCart + rand(-10, 10)));
            $purchase   = max(0, (int) ($basePurchase + rand(-5, 5)));
            $conversion = $visitor > 0 ? round(($purchase / $visitor) * 100, 1) : 0;

            $visitorData[]    = $visitor;
            $cartData[]       = $cart;
            $purchaseData[]   = $purchase;
            $conversionData[] = $conversion;
        }

        return [
            'visitor'    => $visitorData,
            'cart'       => $cartData,
            'purchase'   => $purchaseData,
            'conversion' => $conversionData,
        ];
    }

    /**
     * 获取小时访问量倍数
     */
    private function getHourMultiplier(int $hour): float
    {
        // 模拟一天中的访问模式
        if ($hour >= 9 && $hour <= 11) {
            return 1.5; // 上午高峰
        } elseif ($hour >= 14 && $hour <= 16) {
            return 1.3; // 下午高峰
        } elseif ($hour >= 19 && $hour <= 22) {
            return 1.8; // 晚上高峰
        }

        return 1.0; // 正常时间

    }

    /**
     * 获取星期访问量倍数
     */
    private function getDayMultiplier(int $dayOfWeek): float
    {
        // 模拟一周中的访问模式
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            return 1.2; // 周末
        }

        return 1.0; // 工作日

    }

    /**
     * 获取趋势数据的日期倍数
     */
    private function getDayMultiplierForTrend(int $daysAgo): float
    {
        // 让不同天数的数据有明显差异
        switch ($daysAgo) {
            case 0: // 今日
                return 1.0;
            case 1: // 昨日
                return 0.8; // 昨日数据比今日少20%
            case 2: // 前天
                return 0.9;
            case 3: // 3天前
                return 0.85;
            case 4: // 4天前
                return 0.95;
            case 5: // 5天前
                return 0.88;
            case 6: // 6天前
                return 0.92;
            default:
                return 1.0;
        }
    }

    /**
     * 获取日期范围
     */
    private function getDateRange(string $timeRange): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        switch ($timeRange) {
            case 'yesterday':
                return [
                    Carbon::yesterday()->startOfDay(),
                    Carbon::yesterday()->endOfDay(),
                ];
            case 'week':
                // 近7日：包含今天在内的过去7天
                return [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay(),
                ];
            case 'today':
            default:
                return [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->endOfDay(),
                ];
        }
    }

    /**
     * 获取上一周期的日期范围
     */
    private function getPreviousDateRange(string $timeRange): array
    {
        // 验证时间范围参数
        $validTimeRanges = ['today', 'yesterday', 'week'];
        if (! in_array($timeRange, $validTimeRanges)) {
            throw new \InvalidArgumentException(__('admin/dashboard.invalid_time_range', ['timeRange' => $timeRange, 'validRanges' => implode(', ', $validTimeRanges)]));
        }

        switch ($timeRange) {
            case 'yesterday':
                // 前天的数据
                return [
                    Carbon::yesterday()->subDay()->startOfDay(),
                    Carbon::yesterday()->subDay()->endOfDay(),
                ];
            case 'week':
                // 前一周的7天数据（第8-14天前）
                return [
                    Carbon::now()->subDays(13)->startOfDay(),
                    Carbon::now()->subDays(7)->endOfDay(),
                ];
            case 'today':
            default:
                // 昨天的数据
                return [
                    Carbon::yesterday()->startOfDay(),
                    Carbon::yesterday()->endOfDay(),
                ];
        }
    }

    /**
     * 获取访客统计
     */
    private function getVisitorStats(array $dateRange, array $previousDateRange): array
    {
        $current = ProductView::whereBetween('created_at', $dateRange)
            ->distinct(['customer_id', 'session_id', 'ip'])
            ->count();

        $previous = ProductView::whereBetween('created_at', $previousDateRange)
            ->distinct(['customer_id', 'session_id', 'ip'])
            ->count();

        $percentage = $this->calculatePercentage($current, $previous);

        return [
            'current'    => $current,
            'previous'   => $previous,
            'percentage' => $percentage,
            'trend'      => $percentage >= 0 ? 'up' : 'down',
        ];
    }

    /**
     * 获取加购统计 - 基于下单的独立用户数量计算
     */
    private function getCartStats(array $dateRange, array $previousDateRange): array
    {
        // 使用订单的customer_id去重统计下单的独立用户数量
        $current = Order::whereBetween('created_at', $dateRange)
            ->distinct('customer_id')
            ->count();

        $previous = Order::whereBetween('created_at', $previousDateRange)
            ->distinct('customer_id')
            ->count();

        $percentage = $this->calculatePercentage($current, $previous);

        return [
            'current'    => $current,
            'previous'   => $previous,
            'percentage' => $percentage,
            'trend'      => $percentage >= 0 ? 'up' : 'down',
        ];
    }

    /**
     * 获取购买统计
     */
    private function getPurchaseStats(array $dateRange, array $previousDateRange): array
    {
        $current = Order::whereBetween('created_at', $dateRange)
            ->whereIn('status', StateMachineService::getValidStatuses())
            ->distinct('customer_id')
            ->count();

        $previous = Order::whereBetween('created_at', $previousDateRange)
            ->whereIn('status', StateMachineService::getValidStatuses())
            ->distinct('customer_id')
            ->count();

        $percentage = $this->calculatePercentage($current, $previous);

        return [
            'current'    => $current,
            'previous'   => $previous,
            'percentage' => $percentage,
            'trend'      => $percentage >= 0 ? 'up' : 'down',
        ];
    }

    /**
     * 获取转化率统计
     */
    private function getConversionStats(array $visitorStats, array $cartStats, array $purchaseStats): array
    {
        $types = [
            'visitor-to-purchase' => [
                'rate'          => $visitorStats['current']           > 0 ? round(($purchaseStats['current'] / $visitorStats['current']) * 100, 1) : 0,
                'previous_rate' => $visitorStats['previous']          > 0 ? round(($purchaseStats['previous'] / $visitorStats['previous']) * 100, 1) : 0,
            ],
            'visitor-to-cart' => [
                'rate'          => $visitorStats['current']           > 0 ? round(($cartStats['current'] / $visitorStats['current']) * 100, 1) : 0,
                'previous_rate' => $visitorStats['previous']          > 0 ? round(($cartStats['previous'] / $visitorStats['previous']) * 100, 1) : 0,
            ],
            'cart-to-purchase' => [
                'rate'          => $cartStats['current']           > 0 ? round(($purchaseStats['current'] / $cartStats['current']) * 100, 1) : 0,
                'previous_rate' => $cartStats['previous']          > 0 ? round(($purchaseStats['previous'] / $cartStats['previous']) * 100, 1) : 0,
            ],
        ];

        foreach ($types as $key => &$type) {
            $change         = $type['rate'] - $type['previous_rate'];
            $type['change'] = round($change, 1);
            $type['trend']  = $change >= 0 ? 'up' : 'down';
        }

        return $types;
    }

    /**
     * 获取每日趋势数据
     */
    private function getDailyTrends(int $daysAgo = 0): array
    {
        $date  = Carbon::now()->subDays($daysAgo);
        $hours = range(0, 23);

        $pvData = [];
        $uvData = [];

        foreach ($hours as $hour) {
            $startTime = $date->copy()->hour($hour)->minute(0)->second(0);
            $endTime   = $date->copy()->hour($hour)->minute(59)->second(59);

            // PV: 总页面浏览量
            $pv = ProductView::whereBetween('created_at', [$startTime, $endTime])->count();

            // UV: 独立访客数
            $uv = ProductView::whereBetween('created_at', [$startTime, $endTime])
                ->distinct(['customer_id', 'session_id', 'ip'])
                ->count();

            $pvData[] = $pv;
            $uvData[] = $uv;
        }

        return [
            'labels' => array_map(fn ($h) => sprintf('%02d:00', $h), $hours),
            'pv'     => $pvData,
            'uv'     => $uvData,
        ];
    }

    /**
     * 获取周趋势数据
     */
    private function getWeeklyTrends(): array
    {
        $days   = [];
        $pvData = [];
        $uvData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date      = Carbon::now()->subDays($i);
            $startTime = $date->copy()->startOfDay();
            $endTime   = $date->copy()->endOfDay();

            $days[] = $date->format('m/d');

            $pv = ProductView::whereBetween('created_at', [$startTime, $endTime])->count();
            $uv = ProductView::whereBetween('created_at', [$startTime, $endTime])
                ->distinct(['customer_id', 'session_id', 'ip'])
                ->count();

            $pvData[] = $pv;
            $uvData[] = $uv;
        }

        return [
            'labels' => $days,
            'pv'     => $pvData,
            'uv'     => $uvData,
        ];
    }

    /**
     * 计算百分比变化
     */
    private function calculatePercentage(int $current, int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * 从URL中提取域名
     */
    private function extractDomain(string $url): string
    {
        if (empty($url)) {
            return '';
        }

        // 如果URL不包含协议，添加http://
        if (! preg_match('/^https?:\/\//', $url)) {
            $url = 'http://' . $url;
        }

        $parsedUrl = parse_url($url);
        $host      = $parsedUrl['host'] ?? '';

        // 移除www前缀进行统一比较
        return preg_replace('/^www\./', '', strtolower($host));
    }

    /**
     * 检查域名是否匹配指定的域名列表
     */
    private function isDomainMatch(string $domain, array $targetDomains): bool
    {
        if (empty($domain)) {
            return false;
        }

        foreach ($targetDomains as $targetDomain) {
            // 移除www前缀进行比较
            $normalizedTarget = preg_replace('/^www\./', '', strtolower($targetDomain));

            if ($domain === $normalizedTarget || str_ends_with($domain, '.' . $normalizedTarget)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 生成缓存键
     */
    private function generateCacheKey(string ...$parts): string
    {
        $key = 'dashboard_' . implode('_', $parts);

        // 添加时间戳以确保缓存键的唯一性（按小时）
        $hour = date('Y-m-d-H');

        return $key . '_' . $hour;
    }

    /**
     * 获取缓存状态
     */
    public function getCacheStatus(): array
    {
        return [
            'enabled'              => $this->enableCache,
            'duration'             => $this->cacheDuration,
            'use_mock_data'        => $this->useMockData,
            'mock_data_multiplier' => $this->mockDataMultiplier,
        ];
    }

    /**
     * 清理Dashboard缓存
     */
    public function clearDashboardCache(): void
    {
        if (! $this->enableCache) {
            return; // 如果缓存已禁用，无需清理
        }

        $patterns = [
            'dashboard_basic_stats_*',
            'dashboard_trend_data_*',
            'dashboard_source_analysis_*',
            'dashboard_funnel_data_*',
            'dashboard_product_ranking_*',
        ];

        foreach ($patterns as $pattern) {
            // 这里需要根据实际使用的缓存驱动来实现
            // 如果是Redis，可以使用SCAN命令
            // 如果是文件缓存，可以遍历缓存目录
            Cache::flush(); // 简单实现：清理所有缓存

            break; // 只需要执行一次
        }
    }
}
