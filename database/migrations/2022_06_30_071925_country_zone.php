<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CountryZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('code', 16);
            $table->integer('sort_order');
            $table->tinyInteger('status');
            $table->timestamps();
        });
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id');
            $table->string('name', 64);
            $table->string('code', 16);
            $table->integer('sort_order');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('countries');
        Schema::dropIfExists('zones');
    }
}
