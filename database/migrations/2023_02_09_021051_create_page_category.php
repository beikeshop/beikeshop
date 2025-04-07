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
        Schema::create('page_categories', function (Blueprint $table) {
            $table->comment('文章分类');
            $table->id()->comment('ID');
            $table->integer('parent_id')->comment('父级分类')->comment('parent_id');
            $table->integer('position')->comment('排序');
            $table->boolean('active')->comment('是否启用');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        Schema::create('page_category_descriptions', function (Blueprint $table) {
            $table->comment('文章分类描述');
            $table->id()->comment('ID');
            $table->integer('page_category_id')->comment('分类 ID')->index('page_category_descriptions_page_category_id');
            $table->string('locale')->comment('语言');
            $table->string('title', 255)->comment('标题');
            $table->text('summary')->comment('分类简介');
            $table->string('meta_title', 255)->comment('meta 标题');
            $table->string('meta_description', 500)->comment('meta 描述');
            $table->string('meta_keywords', 255)->comment('meta 关键字');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->integer('page_category_id')->comment('文章分类ID')->after('id')->index('pages_page_category_id');
            $table->string('author')->comment('作者')->after('position');
            $table->integer('views')->comment('查看数')->after('position');
        });

        Schema::table('page_descriptions', function (Blueprint $table) {
            $table->string('summary')->comment('文章摘要')->after('title');
        });

        Schema::create('page_products', function (Blueprint $table) {
            $table->comment('文章产品关联');
            $table->id()->comment('ID');
            $table->integer('page_id')->comment('文章 ID')->index('page_products_page_id');
            $table->integer('product_id')->comment('产品 ID')->index('page_products_product_id');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
