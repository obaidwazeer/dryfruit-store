<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->boolean('allow_cod')
                ->default(true)
                ->after('status');

            $table->boolean('allow_online_payment')
                ->default(false)
                ->after('allow_cod');

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn([
                'allow_cod',
                'allow_online_payment',
            ]);

        });
    }
};
