<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number')->unique();

            // Customer information snapshot
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');

            // Shipping address snapshot
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_postal_code')->nullable();

            // Pricing
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Order state
            $table->string('status', 30)->default('pending');

            // Payment
            $table->string('payment_method', 30)->default('cod');
            $table->string('payment_status', 30)->default('pending');

            $table->text('customer_notes')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('payment_status');
            $table->index('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
