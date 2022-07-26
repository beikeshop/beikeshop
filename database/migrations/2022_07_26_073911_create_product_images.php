<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->json('images')->nullable();
        });
        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->json('images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('images');
            $table->string('image')->default('');
        });
        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropColumn('images');
            $table->string('image')->default('');
        });
    }
}
