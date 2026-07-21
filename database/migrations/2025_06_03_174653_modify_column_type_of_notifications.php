<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $driver = DB::getDriverName();

        Schema::table('notifications', function (Blueprint $table) use ($driver) {
            // 添加影子列
            $table->bigInteger('notifiable_id_new')->nullable()->after('notifiable_id');

            // 添加索引（可选，提升数据迁移性能）
            $table->index(['notifiable_id_new'], 'idx_notifiable_id_new');

            // 添加迁移状态标记
            $table->boolean('migrated')->default(false)->after('notifiable_id_new');
            $table->index(['migrated'], 'idx_migrated');
        });

        // 创建注释（PostgreSQL）
        if ($driver === 'pgsql') {
            DB::statement("COMMENT ON COLUMN notifications.notifiable_id_new IS '影子列：迁移中的BIGINT类型'");
            DB::statement("COMMENT ON COLUMN notifications.migrated IS '数据迁移状态标记'");
        }
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['notifiable_id_new', 'migrated']);
        });
    }
};
