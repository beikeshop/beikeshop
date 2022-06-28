<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('avatar')->default('');
            $table->unsignedInteger('customer_group_id');
            $table->unsignedInteger('language_id');
            $table->text('cart')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('code', 40)->default('');
            $table->string('from', 16)->default('');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 12, 4);
            $table->decimal('reward_point_factor', 12, 4);
            $table->decimal('use_point_factor', 12, 4);
            $table->decimal('discount_factor', 12, 4);
            $table->integer('level');
            $table->timestamps();
        });

        Schema::create('customer_group_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_group_id');
            $table->unsignedInteger('language_id');
            $table->string('name', 256);
            $table->text('description');
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
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_groups');
        Schema::dropIfExists('customer_group_descriptions');
    }
}
