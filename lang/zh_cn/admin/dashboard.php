<?php

/**
 * dashboard.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // 原有翻译
    'product_total' => '新增商品',
    'customer_view' => '用户访问量',
    'order_total'   => '订单量',
    'customer_new'  => '新增用户',
    'order_amount'  => '销售额',
    'today'         => '今日',
    'yesterday'     => '昨日',
    'day_before'    => '较前一日',
    'order_report'  => '订单统计',
    'latest_month'  => '一个月',
    'latest_week'   => '一周',
    'latest_year'   => '一年',

    // Dashboard 新增翻译
    'page_title'               => '实时监控电商平台关键指标和业务表现',
    'visitor_count'            => '访客人数',
    'cart_count'               => '加购人数',
    'purchase_count'           => '购买人数',
    'conversion_rate'          => '转化率',
    'vs_yesterday'             => 'vs 昨日',
    'export_report'            => '导出报表',
    'refresh_data'             => '刷新数据',
    'visitor_trend'            => '访客趋势',
    'customer_source_analysis' => '客户来源分析',
    'loading_source_data'      => '正在加载客户来源数据...',
    'loading'                  => '加载中...',
    'order_funnel'             => '下单漏斗',
    'refresh_funnel_data'      => '刷新漏斗数据',
    'hot_products'             => '热销商品',
    'slow_products'            => '滞销商品',
    'sort_options'             => '排序选项',
    'sort_by_sales_desc'       => '按销量降序',
    'sort_by_sales_asc'        => '按销量升序',
    'sort_by_price_desc'       => '按价格降序',
    'sort_by_price_asc'        => '按价格升序',
    'loading_hot_products'     => '正在加载热销商品数据...',
    'loading_slow_products'    => '正在加载滞销商品数据...',
    'near_7_days'              => '近7日',

    // 转化率选项
    'visitor_to_purchase' => '访客到购买转化率',
    'visitor_to_cart'     => '访客到加购转化率',
    'cart_to_purchase'    => '加购到购买转化率',

    // 错误消息
    'invalid_time_range'         => '无效的时间范围: :timeRange。支持的范围: :validRanges',
    'invalid_product_type'       => '无效的商品类型: :type。支持的类型: :validTypes',
    'invalid_export_format'      => '无效的导出格式: :format。支持的格式: :validFormats',
    'get_stats_failed'           => '获取统计数据失败: :message',
    'get_trends_failed'          => '获取趋势数据失败: :message',
    'get_mini_chart_failed'      => '获取小型趋势图数据失败: :message',
    'get_source_analysis_failed' => '获取来源分析数据失败: :message',
    'get_funnel_failed'          => '获取漏斗数据失败: :message',
    'get_product_ranking_failed' => '获取商品排行数据失败: :message',
    'export_report_failed'       => '导出报表失败: :message',
    'please_login_first'         => '请先登录',
    'no_export_permission'       => '您没有导出报表的权限',
    'export_rate_limit'          => '导出频率过高，请稍后再试',
    'get_cache_status_failed'    => '获取缓存状态失败: :message',
    'cache_clear_success'        => '缓存清理成功',
    'cache_clear_failed'         => '缓存清理失败: :message',
    'network_error'              => '网络请求失败，请检查网络连接',

    // 客户来源
    'direct_access' => '直接访问',
    'google'        => 'Google',
    'baidu'         => '百度',
    'bing'          => '必应',
    'facebook'      => 'Facebook',
    'twitter'       => 'Twitter',
    'youtube'       => 'YouTube',
    'linkedin'      => 'LinkedIn',
    'instagram'     => 'Instagram',
    'other'         => '其他',
    'no_data'       => '暂无数据',

    // 漏斗数据
    'product_views'       => '商品浏览量',
    'unique_visitors'     => '独立访客量',
    'cart_additions'      => '加购数量',
    'orders'              => '下单数',
    'successful_payments' => '支付成功数',

    // 导出报表相关
    'ranking'      => '排名',
    'product_name' => '商品名称',
    'price'        => '价格',
    'sales'        => '销量',
    'percentage'   => '占比',
    'sales_amount' => '销售额',

    // 报表导出相关
    'report_title'          => 'BeikeShop 数据看板报表',
    'export_time'           => '导出时间',
    'time_range'            => '时间范围',
    'basic_stats'           => '基础统计数据',
    'conversion_data'       => '转化率数据',
    'conversion_funnel'     => '转化漏斗',
    'hot_products_ranking'  => '热销商品排行',
    'slow_products_ranking' => '滞销商品排行',
    'trend_data'            => '趋势数据',

    // 统计指标
    'visitor_count'     => '访客数',
    'cart_count'        => '加购数',
    'purchase_count'    => '成交用户数',
    'current_value'     => '当前值',
    'previous_value'    => '对比值',
    'change_percentage' => '环比变化',
    'trend'             => '趋势',
    'up'                => '上升',
    'down'              => '下降',

    // 转化率相关
    'conversion_type'          => '转化类型',
    'current_conversion_rate'  => '当前转化率',
    'previous_conversion_rate' => '对比转化率',
    'change'                   => '变化',

    // 来源分析相关
    'source'      => '来源',
    'visits'      => '访问量',
    'source_type' => '来源类型',
    'rank'        => '排名',
    'total'       => '总计',

    // 漏斗相关
    'stage'            => '环节',
    'quantity'         => '数量',
    'conversion_rate'  => '转化率',
    'width'            => '宽度',
    'conversion_stage' => '转化环节',
    'user_count'       => '用户数量',
    'loss_rate'        => '流失率',
    'funnel_width'     => '漏斗宽度',

    // 趋势相关
    'time'            => '时间',
    'pv'              => 'PV',
    'uv'              => 'UV',
    'pv_uv_ratio'     => 'PV/UV 比例',
    'summary'         => '汇总',
    'page_views'      => '页面浏览量',
    'unique_visitors' => '独立访客',

    // 商品排行相关
    'hot_products_summary'  => '热销商品汇总',
    'slow_products_summary' => '滞销商品汇总',
];
