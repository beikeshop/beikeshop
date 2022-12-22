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
        if (!Schema::hasColumn('orders', 'shipping_country_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedInteger('shipping_country_id')->after('shipping_country')->comment('国家id');
                $table->unsignedInteger('shipping_zone_id')->after('shipping_country_id')->comment('省份ID');
                $table->unsignedInteger('payment_country_id')->after('payment_country')->comment('国家id');
                $table->unsignedInteger('payment_zone_id')->after('payment_country_id')->comment('省份ID');
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
        //
    }
};
