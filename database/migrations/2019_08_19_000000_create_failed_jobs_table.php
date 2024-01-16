<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->comment('队列失败任务');

            $table->id()->comment('主键ID');
            $table->string('uuid')->unique()->comment('唯一ID');
            $table->text('connection')->comment('链接');
            $table->text('queue')->comment('队列');
            $table->longText('payload')->comment('执行数据');
            $table->longText('exception')->comment('异常');
            $table->timestamp('failed_at')->useCurrent()->comment('时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}
