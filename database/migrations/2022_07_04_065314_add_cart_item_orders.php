<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCartItemOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('carts', 'cart_products');

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('ship_address_id');
            $table->integer('pay_address_id');
            $table->integer('shipping_method_code');
            $table->integer('payment_method_code');
            $table->timestamps();
        });


        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('customer_id');
            $table->integer('customer_group_id');
            $table->integer('shipping_address_id');
            $table->integer('payment_address_id');
            $table->string('customer_name');
            $table->string('email');
            $table->integer('calling_code');
            $table->string('telephone');
            $table->decimal('total', 16, 4);
            $table->string('locale');
            $table->string('currency_code');
            $table->string('currency_value');
            $table->string('ip');
            $table->string('user_agent');
            $table->string('status');
            $table->string('shipping_method_code');
            $table->string('shipping_method_name');
            $table->string('shipping_customer_name');
            $table->string('shipping_calling_code');
            $table->string('shipping_telephone');
            $table->string('shipping_country');
            $table->string('shipping_zone');
            $table->string('shipping_city');
            $table->string('shipping_address_1');
            $table->string('shipping_address_2');
            $table->string('payment_method_code');
            $table->string('payment_method_name');
            $table->string('payment_customer_name');
            $table->string('payment_calling_code');
            $table->string('payment_telephone');
            $table->string('payment_country');
            $table->string('payment_zone');
            $table->string('payment_city');
            $table->string('payment_address_1');
            $table->string('payment_address_2');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('order_number');
            $table->string('product_sku');
            $table->string('name');
            $table->string('image');
            $table->integer('quantity');
            $table->decimal('price', 16, 4);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
