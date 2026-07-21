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
        Schema::table('product_views', function (Blueprint $table) {
            $table->string('referer', 500)->nullable()->comment('来源页面URL')->after('session_id');
            $table->string('user_agent', 500)->nullable()->comment('用户代理')->after('referer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_views', function (Blueprint $table) {
            $table->dropColumn(['referer', 'user_agent']);
        });
    }
};