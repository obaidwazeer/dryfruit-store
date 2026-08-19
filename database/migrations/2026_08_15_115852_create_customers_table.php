<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Customer Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('email')->nullable();

            $table->string('phone')->unique();

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            |
            | Currently the store supports guest checkout, so password is nullable.
            | We can introduce customer accounts later.
            |
            */

            $table->string('password')->nullable();

            $table->rememberToken();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
