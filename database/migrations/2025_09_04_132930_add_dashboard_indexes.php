<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 为 product_views 表添加索引
        Schema::table('product_views', function (Blueprint $table) {
            // 复合索引：created_at + customer_id + session_id + ip (用于访客统计)
            $table->index(['created_at', 'customer_id', 'session_id', 'ip'], 'idx_product_views_visitor_stats');
            
            // 复合索引：created_at + product_id (用于商品浏览量统计)
            $table->index(['created_at', 'product_id'], 'idx_product_views_product_stats');
            
            // 复合索引：created_at + referer (用于来源分析)
            $table->index(['created_at', 'referer'], 'idx_product_views_source_analysis');
        });

        // 为 cart_products 表添加索引
        Schema::table('cart_products', function (Blueprint $table) {
            // 复合索引：created_at + customer_id + session_id (用于加购统计)
            $table->index(['created_at', 'customer_id', 'session_id'], 'idx_cart_products_stats');
        });

        // 为 orders 表添加索引
        Schema::table('orders', function (Blueprint $table) {
            // 复合索引：created_at + status (用于订单统计)
            $table->index(['created_at', 'status'], 'idx_orders_stats');
            
            // 复合索引：created_at + status (用于支付统计)
            $table->index(['created_at', 'status'], 'idx_orders_payment_stats');
        });

        // 为 order_products 表添加索引
        Schema::table('order_products', function (Blueprint $table) {
            // 复合索引：product_id + order_id (用于商品销售排行)
            $table->index(['product_id', 'order_id'], 'idx_order_products_ranking');
        });

        // 为 products 表添加索引（如果还没有的话）
        Schema::table('products', function (Blueprint $table) {
            // 复合索引：active + created_at (用于商品查询)
            $table->index(['active', 'created_at'], 'idx_products_active_created');
        });

        // 为 product_descriptions 表添加索引
        Schema::table('product_descriptions', function (Blueprint $table) {
            // 复合索引：product_id + locale (用于商品名称查询)
            $table->index(['product_id', 'locale'], 'idx_product_descriptions_locale');
        });

        // 为 product_skus 表添加索引
        Schema::table('product_skus', function (Blueprint $table) {
            // 复合索引：product_id + is_default (用于默认SKU查询)
            $table->index(['product_id', 'is_default'], 'idx_product_skus_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 删除 product_views 表的索引
        Schema::table('product_views', function (Blueprint $table) {
            $table->dropIndex('idx_product_views_visitor_stats');
            $table->dropIndex('idx_product_views_product_stats');
            $table->dropIndex('idx_product_views_source_analysis');
        });

        // 删除 cart_products 表的索引
        Schema::table('cart_products', function (Blueprint $table) {
            $table->dropIndex('idx_cart_products_stats');
        });

        // 删除 orders 表的索引
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_stats');
            $table->dropIndex('idx_orders_payment_stats');
        });

        // 删除 order_products 表的索引
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropIndex('idx_order_products_ranking');
        });

        // 删除 products 表的索引
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_active_created');
        });

        // 删除 product_descriptions 表的索引
        Schema::table('product_descriptions', function (Blueprint $table) {
            $table->dropIndex('idx_product_descriptions_locale');
        });

        // 删除 product_skus 表的索引
        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropIndex('idx_product_skus_default');
        });
    }

};
