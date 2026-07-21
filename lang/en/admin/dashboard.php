<?php

/**
 * dashboard.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-09-09 09:09:09
 * @modified   2023-09-08 10:57:53
 */

return [
    // Original translations
    'customer_new'  => 'Customer New',
    'customer_view' => 'Customer View',
    'day_before'    => 'Last day',
    'latest_month'  => 'Latest Month',
    'latest_week'   => 'Latest Week',
    'latest_year'   => 'Latest Year',
    'order_amount'  => 'Order Amount',
    'order_report'  => 'Order Report',
    'order_total'   => 'Order Total',
    'product_total' => 'New product',
    'today'         => 'Today',
    'yesterday'     => 'Yesterday',

    // Dashboard new translations
    'page_title'               => 'Real-time monitoring of key e-commerce platform indicators and business performance',
    'visitor_count'            => 'Visitors',
    'cart_count'               => 'Cart Additions',
    'purchase_count'           => 'Purchases',
    'conversion_rate'          => 'Conversion Rate',
    'vs_yesterday'             => 'vs Yesterday',
    'export_report'            => 'Export Report',
    'refresh_data'             => 'Refresh Data',
    'visitor_trend'            => 'Visitor Trend',
    'customer_source_analysis' => 'Customer Source Analysis',
    'loading_source_data'      => 'Loading customer source data...',
    'loading'                  => 'Loading...',
    'order_funnel'             => 'Order Funnel',
    'refresh_funnel_data'      => 'Refresh Funnel Data',
    'hot_products'             => 'Hot Products',
    'slow_products'            => 'Slow Products',
    'sort_options'             => 'Sort Options',
    'sort_by_sales_desc'       => 'Sort by Sales (Desc)',
    'sort_by_sales_asc'        => 'Sort by Sales (Asc)',
    'sort_by_price_desc'       => 'Sort by Price (Desc)',
    'sort_by_price_asc'        => 'Sort by Price (Asc)',
    'loading_hot_products'     => 'Loading hot products data...',
    'loading_slow_products'    => 'Loading slow products data...',
    'near_7_days'              => 'Last 7 Days',

    // Conversion rate options
    'visitor_to_purchase' => 'Visitor to Purchase Conversion',
    'visitor_to_cart'     => 'Visitor to Cart Conversion',
    'cart_to_purchase'    => 'Cart to Purchase Conversion',

    // Error messages
    'invalid_time_range'         => 'Invalid time range: :timeRange. Supported ranges: :validRanges',
    'invalid_product_type'       => 'Invalid product type: :type. Supported types: :validTypes',
    'invalid_export_format'      => 'Invalid export format: :format. Supported formats: :validFormats',
    'get_stats_failed'           => 'Failed to get statistics: :message',
    'get_trends_failed'          => 'Failed to get trend data: :message',
    'get_mini_chart_failed'      => 'Failed to get mini chart data: :message',
    'get_source_analysis_failed' => 'Failed to get source analysis data: :message',
    'get_funnel_failed'          => 'Failed to get funnel data: :message',
    'get_product_ranking_failed' => 'Failed to get product ranking data: :message',
    'export_report_failed'       => 'Failed to export report: :message',
    'please_login_first'         => 'Please login first',
    'no_export_permission'       => 'You do not have permission to export reports',
    'export_rate_limit'          => 'Export rate limit exceeded, please try again later',
    'get_cache_status_failed'    => 'Failed to get cache status: :message',
    'cache_clear_success'        => 'Cache cleared successfully',
    'cache_clear_failed'         => 'Failed to clear cache: :message',
    'network_error'              => 'Network request failed, please check your connection',

    // Customer sources
    'direct_access' => 'Direct Access',
    'google'        => 'Google',
    'baidu'         => 'Baidu',
    'bing'          => 'Bing',
    'facebook'      => 'Facebook',
    'twitter'       => 'Twitter',
    'youtube'       => 'YouTube',
    'linkedin'      => 'LinkedIn',
    'instagram'     => 'Instagram',
    'other'         => 'Other',
    'no_data'       => 'No Data',

    // Funnel data
    'product_views'       => 'Product Views',
    'unique_visitors'     => 'Unique Visitors',
    'cart_additions'      => 'Cart Additions',
    'orders'              => 'Orders',
    'successful_payments' => 'Successful Payments',

    // Export report related
    'ranking'      => 'Ranking',
    'product_name' => 'Product Name',
    'price'        => 'Price',
    'sales'        => 'Sales',
    'percentage'   => 'Percentage',
    'sales_amount' => 'Sales Amount',

    // Report export related
    'report_title'          => 'BeikeShop Data Dashboard Report',
    'export_time'           => 'Export Time',
    'time_range'            => 'Time Range',
    'basic_stats'           => 'Basic Statistics Data',
    'conversion_data'       => 'Conversion Rate Data',
    'conversion_funnel'     => 'Conversion Funnel',
    'hot_products_ranking'  => 'Hot Products Ranking',
    'slow_products_ranking' => 'Slow Products Ranking',
    'trend_data'            => 'Trend Data',

    // Statistics indicators
    'visitor_count'     => 'Visitor Count',
    'cart_count'        => 'Cart Count',
    'purchase_count'    => 'Purchase Count',
    'current_value'     => 'Current Value',
    'previous_value'    => 'Previous Value',
    'change_percentage' => 'Change Percentage',
    'trend'             => 'Trend',
    'up'                => 'Up',
    'down'              => 'Down',

    // Conversion rate related
    'conversion_type'          => 'Conversion Type',
    'current_conversion_rate'  => 'Current Conversion Rate',
    'previous_conversion_rate' => 'Previous Conversion Rate',
    'change'                   => 'Change',

    // Source analysis related
    'source'      => 'Source',
    'visits'      => 'Visits',
    'source_type' => 'Source Type',
    'rank'        => 'Rank',
    'total'       => 'Total',

    // Funnel related
    'stage'            => 'Stage',
    'quantity'         => 'Quantity',
    'conversion_rate'  => 'Conversion Rate',
    'width'            => 'Width',
    'conversion_stage' => 'Conversion Stage',
    'user_count'       => 'User Count',
    'loss_rate'        => 'Loss Rate',
    'funnel_width'     => 'Funnel Width',

    // Trend related
    'time'            => 'Time',
    'pv'              => 'PV',
    'uv'              => 'UV',
    'pv_uv_ratio'     => 'PV/UV Ratio',
    'summary'         => 'Summary',
    'page_views'      => 'Page Views',
    'unique_visitors' => 'Unique Visitors',

    // Product ranking related
    'hot_products_summary'  => 'Hot Products Summary',
    'slow_products_summary' => 'Slow Products Summary',
];
