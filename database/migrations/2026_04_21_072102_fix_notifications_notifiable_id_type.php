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
        $driver = Schema::getConnection()->getDriverName();
        
        // 直接修改列类型为BIGINT
        if (Schema::hasColumn('notifications', 'notifiable_id')) {
            if ($driver === 'pgsql') {
                // PostgreSQL需要先删除列再重新添加
                DB::statement('ALTER TABLE notifications DROP COLUMN notifiable_id');
                DB::statement('ALTER TABLE notifications ADD COLUMN notifiable_id BIGINT');
                // PostgreSQL使用单独的COMMENT语句
                DB::statement('COMMENT ON COLUMN notifications.notifiable_id IS \'通知关联模型ID\'');
            } else {
                // 其他数据库可以直接修改类型
                Schema::table('notifications', function (Blueprint $table) {
                    $table->bigInteger('notifiable_id')->comment('通知关联模型ID')->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();
        
        // 恢复为UUID类型
        if (Schema::hasColumn('notifications', 'notifiable_id')) {
            if ($driver === 'pgsql') {
                // PostgreSQL需要先删除列再重新添加
                DB::statement('ALTER TABLE notifications DROP COLUMN notifiable_id');
                DB::statement('ALTER TABLE notifications ADD COLUMN notifiable_id UUID');
                // PostgreSQL使用单独的COMMENT语句
                DB::statement('COMMENT ON COLUMN notifications.notifiable_id IS \'通知关联模型ID\'');
            } else {
                // 其他数据库可以直接修改类型
                Schema::table('notifications', function (Blueprint $table) {
                    $table->uuid('notifiable_id')->comment('通知关联模型ID')->change();
                });
            }
        }
    }
};