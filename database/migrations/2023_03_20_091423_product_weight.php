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
        if (Schema::hasColumn('products', 'weight')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->float('weight')->comment('重量')->default(0)->after('tax_class_id');
            $table->string('weight_class')->comment('重量单位')->default('')->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('products', 'weight')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('weight_class');
        });
    }
};
