<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('order_shipments')) {
            Schema::create('order_shipments', function (Blueprint $table) {
                $table->comment('订单发货表');
                $table->id()->comment('ID');
                $table->integer('order_id')->comment('订单ID')->index('order_id');
                $table->string('express_code')->comment('快递公司编码');
                $table->string('express_company')->comment('快递公司名称');
                $table->string('express_number')->comment('运单号');
                $table->timestamp('created_at')->nullable()->comment('创建时间');
                $table->timestamp('updated_at')->nullable()->comment('更新时间');
            });
        }

        if (!Schema::hasColumn('orders', 'shipping_zipcode')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('shipping_zipcode')->after('shipping_address_2')->comment('配送地址邮编');
                $table->string('payment_zipcode')->after('payment_address_2')->comment('发票地址邮编');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shipments');
    }
};
