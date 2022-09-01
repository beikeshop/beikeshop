<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('name');
            $table->string('phone');
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('zone_id');
            $table->string('zone');
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city');
            $table->string('zipcode');
            $table->string('address_1');
            $table->string('address_2');
            $table->timestamps();
        });


        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active');
            $table->string('locale')->default('');
            $table->timestamps();
        });


        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('first');
            $table->string('logo');
            $table->integer('sort_order');
            $table->integer('status');
            $table->timestamps();
        });


        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('shipping_address_id');
            $table->string('shipping_method_code');
            $table->integer('payment_address_id');
            $table->string('payment_method_code');
            $table->timestamps();
        });
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->boolean('selected');
            $table->integer('product_id');
            $table->integer('product_sku_id');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->integer('position')->default(0);
            $table->boolean('active');
            $table->timestamps();
        });
        Schema::create('category_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('locale');
            $table->string('name');
            $table->text('content');
            $table->string('meta_title')->default('');
            $table->string('meta_description')->default('');
            $table->string('meta_keyword')->default('');
            $table->timestamps();
        });
        Schema::create('category_paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('path_id');
            $table->integer('level');
            $table->timestamps();
        });


        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('code', 16);
            $table->integer('sort_order');
            $table->tinyInteger('status');
            $table->timestamps();
        });


        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('code', 16);
            $table->string('symbol_left', 16);
            $table->string('symbol_right', 16);
            $table->char('decimal_place', 1);
            $table->double('value', 15, 8);
            $table->tinyInteger('status');
            $table->timestamps();
        });


        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('avatar')->default('');
            $table->unsignedInteger('customer_group_id');
            $table->unsignedInteger('address_id')->default(0);
            $table->string('locale', 10);
            $table->tinyInteger('status')->default(0);
            $table->string('code', 40)->default('');
            $table->string('from', 16)->default('');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 12, 4);
            $table->decimal('reward_point_factor', 12, 4);
            $table->decimal('use_point_factor', 12, 4);
            $table->decimal('discount_factor', 12, 4);
            $table->integer('level');
            $table->timestamps();
        });
        Schema::create('customer_group_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_group_id');
            $table->string('locale', 10);
            $table->string('name', 256);
            $table->text('description');
            $table->timestamps();
        });
        Schema::create('customer_wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('product_id');
            $table->timestamps();
        });


        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('code', 16);
            $table->string('locale', 255);
            $table->string('image', 255);
            $table->integer('sort_order');
            $table->tinyInteger('status');
            $table->timestamps();
        });


        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('customer_id');
            $table->integer('customer_group_id');
            $table->integer('shipping_address_id');
            $table->integer('payment_address_id');
            $table->string('customer_name');
            $table->string('email');
            $table->integer('calling_code');
            $table->string('telephone');
            $table->decimal('total', 16, 4);
            $table->string('locale');
            $table->string('currency_code');
            $table->string('currency_value');
            $table->string('ip');
            $table->string('user_agent');
            $table->string('status');
            $table->string('shipping_method_code');
            $table->string('shipping_method_name');
            $table->string('shipping_customer_name');
            $table->string('shipping_calling_code');
            $table->string('shipping_telephone');
            $table->string('shipping_country');
            $table->string('shipping_zone');
            $table->string('shipping_city');
            $table->string('shipping_address_1');
            $table->string('shipping_address_2');
            $table->string('payment_method_code');
            $table->string('payment_method_name');
            $table->string('payment_customer_name');
            $table->string('payment_calling_code');
            $table->string('payment_telephone');
            $table->string('payment_country');
            $table->string('payment_zone');
            $table->string('payment_city');
            $table->string('payment_address_1');
            $table->string('payment_address_2');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('order_number');
            $table->string('product_sku');
            $table->string('name');
            $table->string('image');
            $table->integer('quantity');
            $table->decimal('price', 16, 4);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('status');
            $table->boolean('notify');
            $table->text('comment');
            $table->timestamps();
        });
        Schema::create('order_totals', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('code');
            $table->string('value');
            $table->string('title');
            $table->json('reference');
            $table->timestamps();
        });


        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('position');
            $table->boolean('active');
            $table->timestamps();
        });
        Schema::create('page_descriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('page_id');
            $table->string('locale');
            $table->string('title');
            $table->text('content');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keyword');
            $table->timestamps();
        });


        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('类型: shipping, payment');
            $table->string('code')->comment('唯一标识');
            $table->timestamps();
        });


        Schema::create('products', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedInteger('brand_id')->index();
            $table->json('images')->nullable();
            $table->decimal('price')->default(0);
            $table->string('video')->default('');
            $table->integer('position')->default(0);
            $table->boolean('active')->default(0);
            $table->json('variables')->nullable();
            $table->integer('tax_class_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });
        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('locale');
            $table->string('name');
            $table->text('content');
            $table->string('meta_title')->default('');
            $table->string('meta_description')->default('');
            $table->string('meta_keyword')->default('');
            $table->timestamps();
        });
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedBigInteger('product_id');
            $table->json('variants')->nullable();
            $table->integer('position')->default(0);
            $table->json('images')->nullable();
            $table->string('model')->default('');
            $table->string('sku')->default('');
            $table->double('price')->default(0);
            $table->double('origin_price')->default(0);
            $table->double('cost_price')->default(0);
            $table->integer('quantity')->default(0);
            $table->boolean('is_default');
            $table->timestamps();
        });


        // 区域组, 比如江浙沪, 中国西部
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        // 区域组与国家省市县关联表
        Schema::create('region_zones', function (Blueprint $table) {
            $table->id();
            $table->integer('region_id');
            $table->integer('country_id');
            $table->integer('zone_id');
            $table->timestamps();
        });


        Schema::create('rmas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('order_product_id');
            $table->unsignedInteger('customer_id');
            $table->string('name');
            $table->string('email');
            $table->string('telephone');
            $table->string('product_name');
            $table->string('sku');
            $table->integer('quantity');
            $table->tinyInteger('opened');
            $table->unsignedInteger('rma_reason_id');
            $table->string('type'); // 售后服务类型：退货、换货、维修、补发商品、仅退款
            $table->string('status'); //
            $table->text('comment');
            $table->timestamps();
        });
        Schema::create('rma_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rma_id');
            $table->string('status');
            $table->tinyInteger('notify');
            $table->text('comment');
            $table->timestamps();
        });
        Schema::create('rma_reasons', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // 值示例: {"en":"cannot to use","zh_cn":"无法使用"}
            $table->timestamps();
        });


        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('类型,包括 system、plugin');
            $table->string('space')->comment('配置组, 比如 stripe, paypal, flat_shipping');
            $table->string('name')->comment('配置名称, 类似字段名');
            $table->text('value')->comment('配置值');
            $table->boolean('json')->default(false);
            $table->timestamps();
        });


        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('region_id');
            $table->string('name');
            $table->string('rate');
            $table->enum('type', ['percent', 'flat']);
            $table->timestamps();
        });
        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_rate_id');
            $table->enum('based', ['store', 'payment', 'shipping']);
            $table->integer('priority');
            $table->timestamps();
        });


        Schema::create('verify_codes', function (Blueprint $table) {
            $table->id();
            $table->string('account', 256);
            $table->string('code', 16);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id');
            $table->string('name', 64);
            $table->string('code', 16);
            $table->integer('sort_order');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
