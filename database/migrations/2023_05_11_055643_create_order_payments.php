<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('order_payments')) {
            return;
        }

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('transaction_id')->nullable();
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->text('callback')->nullable();
            $table->text('receipt')->nullable();
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
        Schema::dropIfExists('order_payments');
    }
};
