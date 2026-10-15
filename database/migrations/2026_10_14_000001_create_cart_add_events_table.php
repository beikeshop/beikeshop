<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection((string) config('database.default', 'mysql'))
            ->create('cart_add_events', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('visitor_key', 64)->comment('访客会话标识的 SHA-256 摘要');
                $table->unsignedBigInteger('product_id');
                $table->string('product_sku')->default('');
                $table->string('product_name')->default('');
                $table->string('sku_model')->default('');
                $table->unsignedInteger('quantity')->default(1);
                $table->timestamps();

                $table->index(['customer_id', 'created_at'], 'cart_add_events_customer_time');
                $table->index(['visitor_key', 'created_at'], 'cart_add_events_visitor_time');
                $table->index('created_at', 'cart_add_events_created_at');
            });
    }

    public function down(): void
    {
        Schema::connection((string) config('database.default', 'mysql'))
            ->dropIfExists('cart_add_events');
    }
};
