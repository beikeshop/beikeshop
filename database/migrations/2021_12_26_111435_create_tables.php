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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active');
            $table->string('locale')->default('');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->integer('position')->default(0);
            $table->boolean('active');
            $table->timestamps();
        });

        Schema::create('category_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('locale');
            $table->string('name');
            $table->text('content');
            $table->string('meta_title')->default('');
            $table->string('meta_description')->default('');
            $table->string('meta_keyword')->default('');
            $table->timestamps();
        });

        Schema::create('category_paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('path_id');
            $table->integer('level');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->string('image')->default('');
            $table->decimal('price')->default(0);
            $table->string('video')->default('');
            $table->integer('position')->default(0);
            $table->boolean('active')->default(0);
            $table->json('variables')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });

        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('locale');
            $table->string('name');
            $table->text('content');
            $table->string('meta_title')->default('');
            $table->string('meta_description')->default('');
            $table->string('meta_keyword')->default('');
            $table->timestamps();
        });

        Schema::create('product_skus', function (Blueprint $table) {
            $table->id()->startingValue(100_000);
            $table->unsignedBigInteger('product_id');
            $table->json('variants')->nullable();
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

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->boolean('selected');
            $table->integer('product_id');
            $table->integer('product_sku_id');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('类型,包括 system、plugin');
            $table->string('space')->comment('配置组, 比如 stripe, paypal, flat_shipping');
            $table->string('name')->comment('配置名称, 类似字段名');
            $table->string('value')->comment('配置值');
            $table->boolean('json')->default(false);
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
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_descriptions');
        Schema::dropIfExists('category_paths');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_descriptions');
        Schema::dropIfExists('product_skus');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('settings');
    }
}
