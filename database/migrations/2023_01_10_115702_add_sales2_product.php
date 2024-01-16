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
        if (Schema::hasColumn('products', 'sales')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->integer('sales')->comment('销量')->default(0)->after('tax_class_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('products', 'sales')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sales');
        });
    }
};
