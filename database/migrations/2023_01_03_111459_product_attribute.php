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
        Schema::create('attributes', function (Blueprint $table) {
            $table->comment('属性表');
            $table->id();
            $table->unsignedInteger('attribute_group_id')->comment('属性组 ID')->index('attribute_group_id');
            $table->integer('sort_order')->comment('排序');
            $table->timestamps();
        });
        Schema::create('attribute_descriptions', function (Blueprint $table) {
            $table->comment('属性描述表');
            $table->id();
            $table->unsignedInteger('attribute_id')->comment('属性 ID')->index('attribute_id');
            $table->string('locale')->default('')->comment('语言');
            $table->string('name')->default('')->comment('名称');
            $table->index(['attribute_id', 'locale'], 'attribute_id_locale');
            $table->timestamps();
        });
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->comment('属性值表');
            $table->id();
            $table->unsignedInteger('attribute_id')->comment('属性 ID')->index('attribute_id');
            $table->timestamps();
        });
        Schema::create('attribute_value_descriptions', function (Blueprint $table) {
            $table->comment('属性值描述表');
            $table->id();
            $table->unsignedInteger('attribute_value_id')->comment('属性值 ID')->index('attribute_value_id');
            $table->string('locale')->default('')->comment('语言');
            $table->string('name')->default('')->comment('名称');
            $table->index(['attribute_value_id', 'locale'], 'attribute_value_id_locale');
            $table->timestamps();
        });
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->comment('属性组表');
            $table->id();
            $table->integer('sort_order')->comment('排序');
            $table->timestamps();
        });
        Schema::create('attribute_group_descriptions', function (Blueprint $table) {
            $table->comment('属性组描述表');
            $table->id();
            $table->unsignedInteger('attribute_group_id')->comment('属性组 ID')->index('attribute_group_id');
            $table->string('locale')->default('')->comment('语言');
            $table->string('name')->default('')->comment('名称');
            $table->index(['attribute_group_id', 'locale'], 'attribute_group_id_locale');
            $table->timestamps();
        });
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->comment('产品属性关联表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品 ID')->index('product_id');
            $table->unsignedInteger('attribute_id')->comment('属性 ID')->index('attribute_id');
            $table->unsignedInteger('attribute_value_id')->comment('属性值 ID')->index('attribute_value_id');
            $table->index(['product_id', 'attribute_id'], 'product_id_attribute_id');
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
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_descriptions');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attribute_value_descriptions');
        Schema::dropIfExists('attribute_groups');
        Schema::dropIfExists('attribute_group_descriptions');
        Schema::dropIfExists('product_attributes');
    }
};
