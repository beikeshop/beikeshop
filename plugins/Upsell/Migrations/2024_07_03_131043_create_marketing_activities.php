<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marketing_activities', function (Blueprint $table) {
            $table->comment('营销活动');
            $table->id();
            $table->string("type")->comment("活动类型");
            $table->string("subtype")->comment("活动子类型");
            $table->string("target")->comment("目标SKU");
            $table->string("related")->comment("关联SKU");
            $table->integer("priority")->comment("优先级");
            $table->decimal('price', 16, 4)->comment('营销活动单价');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_activities');
    }
};
