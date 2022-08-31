<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @rma void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @rma void
     */
    public function down()
    {
        Schema::dropIfExists('rmas');
        Schema::dropIfExists('rma_histories');
        Schema::dropIfExists('rma_reasons');
    }
}
