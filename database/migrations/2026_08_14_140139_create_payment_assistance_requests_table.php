<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_assistance_requests', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            */

            $table->string('reference')->unique();


            /*
            |--------------------------------------------------------------------------
            | Customer Information Snapshot
            |--------------------------------------------------------------------------
            */

            $table->string('customer_name');

            $table->string('customer_email')->nullable();

            $table->string('customer_phone');


            /*
            |--------------------------------------------------------------------------
            | Cart Snapshot
            |--------------------------------------------------------------------------
            |
            | We store the cart exactly as it existed when the customer
            | requested payment assistance.
            |
            */

            $table->json('cart');


            /*
            |--------------------------------------------------------------------------
            | Pricing Snapshot
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 12, 2);


            /*
            |--------------------------------------------------------------------------
            | Assistance Reason
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | mixed_payment_methods
            | no_payment_method
            |
            */

            $table->string('reason', 50);


            /*
            |--------------------------------------------------------------------------
            | Request Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30)
                ->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Admin Notes
            |--------------------------------------------------------------------------
            */

            $table->text('admin_notes')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('status');

            $table->index('customer_phone');

            $table->index('reason');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_assistance_requests');
    }
};
