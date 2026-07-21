<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 检查是否已经执行过此迁移
        $columns = Schema::getColumnListing('brands');

        // 如果active字段不存在且status字段存在，则执行迁移
        if (!in_array('active', $columns) && in_array('status', $columns)) {
            Schema::table('brands', function (Blueprint $table) {
                // 添加新的active字段
                $table->integer('active')->default(true)->comment('是否启用')->after('sort_order');
            });

            // 将status字段的数据迁移到active字段
            DB::statement('UPDATE brands SET active = CASE WHEN status = 1 THEN 1 ELSE 0 END');

            Schema::table('brands', function (Blueprint $table) {
                // 删除旧的status字段
                $table->dropColumn('status');
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
        Schema::table('brands', function (Blueprint $table) {
            // 添加回status字段
            $table->integer('status')->default(1)->comment('状态')->after('sort_order');
        });

        // 将active字段的数据迁移回status字段
        DB::statement('UPDATE brands SET status = CASE WHEN active = 1 THEN 1 ELSE 0 END');

        Schema::table('brands', function (Blueprint $table) {
            // 删除active字段
            $table->dropColumn('active');
        });
    }
};
