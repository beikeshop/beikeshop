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
        if (Schema::hasTable('order_payments')) {
            return;
        }

        Schema::create('order_payments', function (Blueprint $table) {
            $table->comment('订单支付表');
            $table->id()->comment('ID');
            $table->integer('order_id')->comment('订单 ID');
            $table->string('transaction_id')->nullable()->comment('交易 ID');
            $table->text('request')->nullable()->comment('请求数据');
            $table->text('response')->nullable()->comment('响应数据');
            $table->text('callback')->nullable()->comment('回调数据');
            $table->text('receipt')->nullable()->comment('收据');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
};
