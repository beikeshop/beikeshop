<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('first');
            $table->string('logo');
            $table->integer('sort_order');
            $table->integer('status');
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('manufacturer_id')->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturers');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('manufacturer_id');
        });
    }
}
