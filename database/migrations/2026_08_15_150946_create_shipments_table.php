<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Courier
            |--------------------------------------------------------------------------
            */

            $table->string('courier')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Tracking
            |--------------------------------------------------------------------------
            */

            $table->string('tracking_number')
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Shipment Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Courier Response
            |--------------------------------------------------------------------------
            |
            | Useful when we integrate Leopards API later.
            |
            */

            $table->text('tracking_url')
                ->nullable();

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('shipped_at')
                ->nullable();

            $table->timestamp('delivered_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('status');
            $table->index('courier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
