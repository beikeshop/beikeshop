<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 区域组, 比如江浙沪, 中国西部
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        // 区域组与国家省市县关联表
        Schema::create('region_zones', function (Blueprint $table) {
            $table->id();
            $table->integer('region_id');
            $table->integer('country_id');
            $table->integer('zone_id');
            $table->timestamps();
        });


        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('region_id');
            $table->string('name');
            $table->string('rate');
            $table->enum('type', ['percent', 'flat']);
            $table->timestamps();
        });

        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_rate_id');
            $table->enum('based', ['store', 'payment', 'shipping']);
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

    }
}
