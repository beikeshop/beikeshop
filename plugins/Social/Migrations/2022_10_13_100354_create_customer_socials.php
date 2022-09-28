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
        Schema::create('customer_socials', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('provider');
            $table->string('user_id');
            $table->string('union_id');
            $table->string('access_token');
            $table->text('extra');
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
        Schema::dropIfExists('customer_socials');
    }
};
