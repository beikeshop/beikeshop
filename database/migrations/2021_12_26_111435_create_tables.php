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
            $table->string('image')->default('');
            $table->string('video')->default('');
            $table->integer('position')->default(0);
            $table->boolean('active');
            $table->json('variables')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_skus', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedBigInteger('product_id');
            $table->string('variants')->default(0);
            $table->integer('position')->default(0);
            $table->string('image')->default('');
            $table->string('model')->default('');
            $table->string('sku')->default('');
            $table->double('price')->default(0);
            $table->double('origin_price')->default(0);
            $table->double('cost_price')->default(0);
            $table->integer('quantity')->default(0);
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
