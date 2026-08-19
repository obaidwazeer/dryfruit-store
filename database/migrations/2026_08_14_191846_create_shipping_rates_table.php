<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Destination
            |--------------------------------------------------------------------------
            */

            $table->string('city')->unique();

            /*
            |--------------------------------------------------------------------------
            | Shipping Rate
            |--------------------------------------------------------------------------
            */

            $table->decimal('rate', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
