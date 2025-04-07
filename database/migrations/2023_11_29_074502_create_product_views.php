<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_views', function (Blueprint $table) {
            $table->comment('商品浏览记录表');
            $table->id()->comment('ID');
            $table->integer('product_id')->nullable()->comment('商品 ID');
            $table->integer('customer_id')->nullable()->comment('客户 ID');
            $table->string('ip')->nullable()->comment('IP');
            $table->string('session_id')->nullable()->comment('Session ID');
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
        Schema::dropIfExists('product_views');
    }
};
