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
        if (Schema::hasTable('jobs')) {
            return;
        }

        Schema::create('jobs', function (Blueprint $table) {
            $table->comment('任务表');
            $table->bigIncrements('id')->comment('ID');
            $table->string('queue')->index()->comment('队列');
            $table->longText('payload')->comment('负载');
            $table->unsignedTinyInteger('attempts')->comment('尝试次数');
            $table->unsignedInteger('reserved_at')->nullable()->comment('保留时间');
            $table->unsignedInteger('available_at')->comment('可用时间');
            $table->unsignedInteger('created_at')->comment('创建时间');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
