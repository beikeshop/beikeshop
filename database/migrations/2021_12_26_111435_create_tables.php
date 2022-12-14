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
            $table->comment('用户地址表');
            $table->id()->comment('ID');
            $table->unsignedInteger('customer_id')->comment('客户 ID')->index('customer_id');
            $table->string('name')->comment('姓名');
            $table->string('phone')->comment('电话');
            $table->unsignedInteger('country_id')->comment('国家 ID')->index('country_id');
            $table->unsignedInteger('zone_id')->comment('省份 ID')->index('zone_id');
            $table->string('zone')->comment('省份名称');
            $table->unsignedInteger('city_id')->nullable()->comment('城市 ID')->index('city_id');
            $table->string('city')->comment('城市名称');
            $table->string('zipcode')->comment('邮编');
            $table->string('address_1')->comment('地址1');
            $table->string('address_2')->comment('地址2');
            $table->timestamps();
        });


        Schema::create('admin_users', function (Blueprint $table) {
            $table->comment('后台管理员表');
            $table->id()->comment('ID');
            $table->string('name')->comment('用户名');
            $table->string('email')->unique()->comment('Email');
            $table->string('password')->comment('密码');
            $table->boolean('active')->comment('是否启用');
            $table->string('locale')->default('')->comment('语言');
            $table->timestamps();
        });


        Schema::create('brands', function (Blueprint $table) {
            $table->comment('产品品牌');
            $table->id()->comment('ID');
            $table->string('name')->comment('名称');
            $table->char('first')->comment('首字母');
            $table->string('logo')->comment('图标');
            $table->integer('sort_order')->comment('排序');
            $table->integer('status')->comment('状态');
            $table->timestamps();
        });


        Schema::create('carts', function (Blueprint $table) {
            $table->comment('购物车');
            $table->id()->comment('ID');
            $table->integer('customer_id')->comment('客户 ID')->index('customer_id');
            $table->integer('shipping_address_id')->comment('配送地址 ID')->index('shipping_address_id');
            $table->string('shipping_method_code')->comment('配送方式 Code');
            $table->integer('payment_address_id')->comment('发票地址 ID')->index('payment_address_id');
            $table->string('payment_method_code')->comment('支付方式 Code');
            $table->timestamps();
        });
        Schema::create('cart_products', function (Blueprint $table) {
            $table->comment('购物车产品明细');
            $table->id()->comment('ID');
            $table->integer('customer_id')->comment('客户 ID')->index('customer_id');
            $table->boolean('selected')->comment('是否选中');
            $table->integer('product_id')->comment('产品 ID')->index('product_id');
            $table->integer('product_sku_id')->comment('产品 SKU ID')->index('product_sku_id');
            $table->unsignedInteger('quantity')->comment('购买数量');
            $table->timestamps();
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->comment('产品分类');
            $table->id()->startingValue(100_000)->comment('ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级分类ID')->index('parent_id');
            $table->integer('position')->default(0)->comment('排序');
            $table->boolean('active')->comment('是否启用');
            $table->timestamps();
        });
        Schema::create('category_descriptions', function (Blueprint $table) {
            $table->comment('产品分类名称、描述等详情');
            $table->id()->comment('ID');
            $table->unsignedBigInteger('category_id')->comment('分类 ID')->index('category_id');
            $table->string('locale')->comment('语言');
            $table->string('name')->comment('名称');
            $table->text('content')->comment('描述');
            $table->string('meta_title')->default('')->comment('meta 标题');
            $table->string('meta_description')->default('')->comment('meta 描述');
            $table->string('meta_keyword')->default('')->comment('meta 关键词');
            $table->timestamps();
        });
        Schema::create('category_paths', function (Blueprint $table) {
            $table->comment('产品分类上下级关系');
            $table->id()->comment('ID');
            $table->unsignedBigInteger('category_id')->comment('分类 ID')->index('category_id');
            $table->unsignedBigInteger('path_id')->comment('分类路径 ID')->index('path_id');
            $table->integer('level')->comment('层级');
            $table->timestamps();
        });


        Schema::create('countries', function (Blueprint $table) {
            $table->comment('国家');
            $table->id()->comment('ID');
            $table->string('name', 64)->comment('名称');
            $table->string('code', 16)->comment('编码');
            $table->integer('sort_order')->comment('排序');
            $table->tinyInteger('status')->comment('是否启用');
            $table->timestamps();
        });


        Schema::create('currencies', function (Blueprint $table) {
            $table->comment('货币');
            $table->id()->comment('ID');
            $table->string('name', 64)->comment('名称');
            $table->string('code', 16)->comment('编码');
            $table->string('symbol_left', 16)->comment('左标识, 比如 $ ￥');
            $table->string('symbol_right', 16)->comment('右标识, 比如 $ ￥');
            $table->char('decimal_place', 1)->comment('小数位数');
            $table->double('value', 15, 8)->comment('默认货币相对当前货币汇率');
            $table->tinyInteger('status')->comment('是否启用');
            $table->timestamps();
        });


        Schema::create('customers', function (Blueprint $table) {
            $table->comment('客户');
            $table->id()->comment('ID');
            $table->string('email')->unique()->comment('Email');
            $table->string('password')->comment('密码');
            $table->string('name')->comment('用户名');
            $table->string('avatar')->default('')->comment('头像');
            $table->unsignedInteger('customer_group_id')->comment('用户组 ID')->index('customer_group_id');
            $table->unsignedInteger('address_id')->default(0)->comment('默认地址 ID')->index('address_id');
            $table->string('locale', 10)->comment('语言');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->string('code', 40)->default('')->comment('找回密码 code');
            $table->string('from', 16)->default('')->comment('注册来源');
            $table->softDeletes()->comment('删除时间');
            $table->timestamps();
        });
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->comment('客户组');
            $table->id()->comment('ID');
            $table->decimal('total', 12, 4)->comment('最低消费额度');
            $table->decimal('reward_point_factor', 12, 4)->comment('奖励积分系数');
            $table->decimal('use_point_factor', 12, 4)->comment('使用积分系数');
            $table->decimal('discount_factor', 12, 4)->comment('优惠折扣系数');
            $table->integer('level')->comment('等级');
            $table->timestamps();
        });
        Schema::create('customer_group_descriptions', function (Blueprint $table) {
            $table->comment('客户组名称、描述');
            $table->id()->comment('ID');
            $table->unsignedInteger('customer_group_id')->comment('客户组 ID')->index('customer_group_id');
            $table->string('locale', 10)->comment('语言');
            $table->string('name', 256)->comment('名称');
            $table->text('description')->comment('描述');
            $table->timestamps();
        });
        Schema::create('customer_wishlists', function (Blueprint $table) {
            $table->comment('客户收藏夹');
            $table->id()->comment('ID');
            $table->unsignedInteger('customer_id')->comment('客户 ID')->index('customer_id');
            $table->unsignedInteger('product_id')->comment('产品 ID')->index('product_id');
            $table->timestamps();
        });


        Schema::create('languages', function (Blueprint $table) {
            $table->comment('语言');
            $table->id()->comment('ID');
            $table->string('name', 64)->comment('名称');
            $table->string('code', 16)->comment('编码');
            $table->string('locale', 255)->comment('浏览器语言标识');
            $table->string('image', 255)->comment('语言图标');
            $table->integer('sort_order')->comment('排序');
            $table->tinyInteger('status')->comment('是否启用');
            $table->timestamps();
        });


        Schema::create('orders', function (Blueprint $table) {
            $table->comment('订单');
            $table->id()->comment('ID');
            $table->string('number')->comment('订单号');
            $table->integer('customer_id')->comment('客户 ID')->index('customer_id');
            $table->integer('customer_group_id')->comment('客户组 ID')->index('customer_group_id');
            $table->integer('shipping_address_id')->comment('配送地址 ID')->index('shipping_address_id');
            $table->integer('payment_address_id')->comment('发票地址 ID')->index('payment_address_id');
            $table->string('customer_name')->comment('客户名称');
            $table->string('email')->comment('客户 Email');
            $table->integer('calling_code')->comment('电话区号');
            $table->string('telephone')->comment('电话号码');
            $table->decimal('total', 16, 4)->comment('总金额');
            $table->string('locale')->comment('语言');
            $table->string('currency_code')->comment('当前货币');
            $table->string('currency_value')->comment('当前汇率');
            $table->string('ip')->comment('下单时 IP');
            $table->text('user_agent')->comment('下单时浏览器信息');
            $table->string('status')->comment('订单状态');
            $table->string('shipping_method_code')->comment('配送方式编码');
            $table->string('shipping_method_name')->comment('配送方式名称');
            $table->string('shipping_customer_name')->comment('配送地址姓名');
            $table->string('shipping_calling_code')->comment('配送地址电话区号');
            $table->string('shipping_telephone')->comment('配送地址电话号码');
            $table->string('shipping_country')->comment('配送地址国家');
            $table->string('shipping_zone')->comment('配送地址省份');
            $table->string('shipping_city')->comment('配送地址城市');
            $table->string('shipping_address_1')->comment('配送地址详情1');
            $table->string('shipping_address_2')->comment('配送地址详情2');
            $table->string('payment_method_code')->comment('支付方式编码');
            $table->string('payment_method_name')->comment('支付方式名称');
            $table->string('payment_customer_name')->comment('发票地址姓名');
            $table->string('payment_calling_code')->comment('发票地址电话区号');
            $table->string('payment_telephone')->comment('发票地址电话号码');
            $table->string('payment_country')->comment('发票地址国家');
            $table->string('payment_zone')->comment('发票地址省份');
            $table->string('payment_city')->comment('发票地址城市');
            $table->string('payment_address_1')->comment('发票地址详情1');
            $table->string('payment_address_2')->comment('发票地址详情1');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间');
        });
        Schema::create('order_products', function (Blueprint $table) {
            $table->comment('订单产品明细');
            $table->id()->comment('ID');
            $table->integer('order_id')->comment('订单 ID')->index('order_id');
            $table->integer('product_id')->comment('产品 ID')->index('product_id');
            $table->string('order_number')->comment('订单号');
            $table->string('product_sku')->comment('产品 SKU');
            $table->string('name')->comment('产品名称');
            $table->string('image')->comment('产品图片');
            $table->integer('quantity')->comment('购买数量');
            $table->decimal('price', 16, 4)->comment('单价');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间');
        });
        Schema::create('order_histories', function (Blueprint $table) {
            $table->comment('订单状态变更历史');
            $table->id()->comment('ID');
            $table->integer('order_id')->comment('订单 ID')->index('order_id');
            $table->string('status')->comment('订单变更状态');
            $table->boolean('notify')->comment('是否通知');
            $table->text('comment')->comment('变更备注');
            $table->timestamps();
        });
        Schema::create('order_totals', function (Blueprint $table) {
            $table->comment('订单金额构成');
            $table->id()->comment('ID');
            $table->integer('order_id')->comment('订单 ID')->index('order_id');
            $table->string('code')->comment('类型编码');
            $table->decimal('value')->comment('金额');
            $table->string('title')->comment('名称');
            $table->json('reference')->comment('附加信息');
            $table->timestamps();
        });


        Schema::create('pages', function (Blueprint $table) {
            $table->comment('单页');
            $table->id()->comment('ID');
            $table->integer('position')->comment('排序');
            $table->boolean('active')->comment('是否启用');
            $table->timestamps();
        });
        Schema::create('page_descriptions', function (Blueprint $table) {
            $table->comment('单页名称、描述等详情');
            $table->id()->comment('ID');
            $table->integer('page_id')->comment('单页 ID')->index('page_id');
            $table->string('locale')->comment('语言');
            $table->string('title')->comment('标题');
            $table->text('content')->comment('内容');
            $table->string('meta_title')->comment('meta 标题');
            $table->string('meta_description')->comment('meta 描述');
            $table->string('meta_keyword')->comment('meta 关键字');
            $table->timestamps();
        });


        Schema::create('plugins', function (Blueprint $table) {
            $table->comment('插件');
            $table->id()->comment('ID');
            $table->string('type')->comment('类型: shipping, payment');
            $table->string('code')->comment('编码, 唯一标识');
            $table->timestamps();
        });


        Schema::create('products', function (Blueprint $table) {
            $table->comment('产品');
            $table->id()->startingValue(100_000)->comment('ID');
            $table->unsignedInteger('brand_id')->index()->comment('品牌 ID')->index('brand_id');
            $table->json('images')->nullable()->comment('图片');
            $table->decimal('price')->default(0)->comment('价格');
            $table->string('video')->default('')->comment('视频');
            $table->integer('position')->default(0)->comment('排序');
            $table->boolean('active')->default(0)->comment('是否启用');
            $table->json('variables')->nullable()->comment('多规格数据');
            $table->integer('tax_class_id')->default(0)->comment('税类 ID')->index('tax_class_id');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间');
        });
        Schema::create('product_categories', function (Blueprint $table) {
            $table->comment('产品所属分类');
            $table->id()->comment('ID');
            $table->unsignedBigInteger('product_id')->comment('产品 ID')->index('product_id');
            $table->unsignedBigInteger('category_id')->comment('分类 ID')->index('category_id');
            $table->timestamps();
        });
        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->comment('产品名称、描述等详情');
            $table->id()->comment('ID');
            $table->unsignedBigInteger('product_id')->comment('产品 ID')->index('product_id');
            $table->string('locale')->comment('语言');
            $table->string('name')->comment('产品名称');
            $table->text('content')->comment('产品描述');
            $table->string('meta_title')->default('')->comment('meta 标题');
            $table->string('meta_description')->default('')->comment('meta 描述');
            $table->string('meta_keyword')->default('')->comment('meta 关键字');
            $table->timestamps();
        });
        Schema::create('product_skus', function (Blueprint $table) {
            $table->comment('产品SKU');
            $table->id()->startingValue(100_000)->comment('ID');
            $table->unsignedBigInteger('product_id')->comment('产品 ID')->index('product_id');
            $table->json('variants')->nullable()->comment('SKU 规格');
            $table->integer('position')->default(0)->comment('排序');
            $table->json('images')->nullable()->comment('图片');
            $table->string('model')->default('')->comment('模型');
            $table->string('sku')->default('')->comment('SKU');
            $table->double('price')->default(0)->comment('价格');
            $table->double('origin_price')->default(0)->comment('划线价');
            $table->double('cost_price')->default(0)->comment('成本价');
            $table->integer('quantity')->default(0)->comment('库存数');
            $table->boolean('is_default')->comment('是否默认');
            $table->timestamps();
        });


        // 区域组, 比如江浙沪, 中国西部
        Schema::create('regions', function (Blueprint $table) {
            $table->comment('区域组, 比如江浙沪, 中国西部');
            $table->id()->comment('ID');
            $table->string('name')->comment('区域组名称');
            $table->string('description')->comment('描述');
            $table->timestamps();
        });
        // 区域组与国家省市县关联表
        Schema::create('region_zones', function (Blueprint $table) {
            $table->comment('区域组与国家省市县关联表');
            $table->id()->comment('ID');
            $table->integer('region_id')->comment('区域组 ID')->index('region_id');
            $table->integer('country_id')->comment('国家 ID')->index('country_id');
            $table->integer('zone_id')->comment('省份 ID')->index('zone_id');
            $table->timestamps();
        });


        Schema::create('rmas', function (Blueprint $table) {
            $table->comment('售后表');
            $table->id()->comment('ID');
            $table->unsignedInteger('order_id')->comment('订单 ID')->index('order_id');
            $table->unsignedInteger('order_product_id')->comment('订单商品明细 ID')->index('order_product_id');
            $table->unsignedInteger('customer_id')->comment('客户 ID')->index('customer_id');
            $table->string('name')->comment('客户姓名');
            $table->string('email')->comment('客户 Email');
            $table->string('telephone')->comment('客户电话');
            $table->string('product_name')->comment('产品名称');
            $table->string('sku')->comment('SKU');
            $table->integer('quantity')->comment('退货数量');
            $table->tinyInteger('opened')->comment('是否已打开包装');
            $table->unsignedInteger('rma_reason_id')->comment('售后原因 ID')->index('rma_reason_id');
            $table->string('type')->comment('售后服务类型：退货、换货、维修、补发商品、仅退款');
            $table->string('status')->comment('状态');
            $table->text('comment')->comment('备注');
            $table->timestamps();
        });
        Schema::create('rma_histories', function (Blueprint $table) {
            $table->comment('售后状态记录');
            $table->id()->comment('ID');
            $table->unsignedInteger('rma_id')->comment('售后 ID')->index('rma_id');
            $table->string('status')->comment('状态');
            $table->tinyInteger('notify')->comment('是否通知');
            $table->text('comment')->comment('备注');
            $table->timestamps();
        });
        Schema::create('rma_reasons', function (Blueprint $table) {
            $table->comment('售后原因');
            $table->id()->comment('ID');
            $table->json('name')->comment('售后原因, 示例: {"en":"cannot to use","zh_cn":"无法使用"}');
            $table->timestamps();
        });


        Schema::create('settings', function (Blueprint $table) {
            $table->comment('系统设置');
            $table->id()->comment('ID');
            $table->string('type')->comment('类型,包括 system、plugin');
            $table->string('space')->comment('配置组, 比如 stripe, paypal, flat_shipping');
            $table->string('name')->comment('配置名称, 类似字段名');
            $table->text('value')->comment('配置值');
            $table->boolean('json')->default(false)->comment('是否json');
            $table->timestamps();
        });


        Schema::create('tax_classes', function (Blueprint $table) {
            $table->comment('税类');
            $table->id()->comment('ID');
            $table->string('title')->comment('税类标题');
            $table->string('description')->comment('税类描述');
            $table->timestamps();
        });
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->comment('税率');
            $table->id()->comment('ID');
            $table->integer('region_id')->comment('区域组 ID')->index('region_id');
            $table->string('name')->comment('名称');
            $table->string('rate')->comment('税率值');
            $table->enum('type', ['percent', 'flat'])->comment('类型, percent:百分比, flat:固定值');
            $table->timestamps();
        });
        Schema::create('tax_rules', function (Blueprint $table) {
            $table->comment('税费规则');
            $table->id()->comment('ID');
            $table->integer('tax_class_id')->comment('税类 ID')->index('tax_class_id');
            $table->integer('tax_rate_id')->comment('税率 ID')->index('tax_rate_id');
            $table->enum('based', ['store', 'payment', 'shipping'])->comment('地址类型');
            $table->integer('priority')->comment('优先级');
            $table->timestamps();
        });


        Schema::create('verify_codes', function (Blueprint $table) {
            $table->comment('验证码');
            $table->id()->comment('ID');
            $table->string('account', 256)->comment('账号');
            $table->string('code', 16)->comment('验证码');
            $table->softDeletes()->comment('删除时间');
            $table->timestamps();
        });


        Schema::create('zones', function (Blueprint $table) {
            $table->comment('省份、州');
            $table->id()->comment('ID');
            $table->unsignedInteger('country_id')->comment('国家 ID')->index('country_id');
            $table->string('name', 64)->comment('名称');
            $table->string('code', 16)->comment('编码');
            $table->integer('sort_order')->comment('排序');
            $table->tinyInteger('status')->comment('是否启用');
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
