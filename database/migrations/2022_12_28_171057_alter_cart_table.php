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
        if (Schema::hasColumn('carts', 'extra')) {
            return;
        }

        Schema::table('carts', function (Blueprint $table) {
            $table->json('extra')->nullable()->after('payment_method_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('carts', 'extra')) {
            return;
        }

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('extra');
        });
    }
};
