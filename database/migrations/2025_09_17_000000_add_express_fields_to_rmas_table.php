<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpressFieldsToRmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rmas', function (Blueprint $table) {
            // 检查字段是否已存在，避免重复添加
            if (!Schema::hasColumn('rmas', 'express_com')) {
                $table->string('express_com')->nullable()->comment('快递公司名称')->after('comment');
            }
            if (!Schema::hasColumn('rmas', 'express_no')) {
                $table->string('express_no')->nullable()->comment('快递单号')->after('express_com');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rmas', function (Blueprint $table) {
            $table->dropColumn(['express_com', 'express_no']);
        });
    }
}