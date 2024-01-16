<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('cart_products', 'product_sku_id')) {
            Schema::table('cart_products', function (Blueprint $table) {
                $table->renameColumn('product_sku_id', 'product_sku');
            });
        }

        Schema::table('cart_products', function (Blueprint $table){
            $table->string('product_sku', '32')->change();
        });

        Schema::table('product_skus', function (Blueprint $table){
            $table->unique('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_products', function (Blueprint $table){
            $table->renameColumn('product_sku', 'product_sku_id');
        });

        Schema::table('cart_products', function (Blueprint $table){
            $table->integer('product_sku_id')->change();
        });
    }
};
