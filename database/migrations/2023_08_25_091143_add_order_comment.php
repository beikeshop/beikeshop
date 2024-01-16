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
        if (!Schema::hasColumn('orders', 'comment')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('comment')->comment('客户备注')->default('')->after('user_agent');
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
        if (Schema::hasColumn('orders', 'comment')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('comment');
            });
        }
    }
};
