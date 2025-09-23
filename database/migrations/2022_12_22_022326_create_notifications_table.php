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
        Schema::create('notifications', function (Blueprint $table) {
            $table->comment('通知表');
            $table->uuid('id')->primary()->comment('UUID');
            $table->string('type')->comment('类型');
            $table->string('notifiable_type')->comment('通知关联模型类型');
            $table->uuid('notifiable_id')->comment('通知关联模型ID');
            $table->text('data')->comment('通知数据');
            $table->timestamp('read_at')->nullable()->comment('打开时间');
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
        Schema::dropIfExists('notifications');
    }
};
