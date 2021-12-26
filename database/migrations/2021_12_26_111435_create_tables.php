<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->string('image');
            $table->string('video');
            $table->integer('sort_order');
            $table->boolean('status');
            $table->json('variable');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_skus', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedBigInteger('product_id');
            $table->string('image');
            $table->string('model');
            $table->string('sku');
            $table->double('price');
            $table->integer('quantity');
            $table->boolean('is_default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_skus');
    }
}
