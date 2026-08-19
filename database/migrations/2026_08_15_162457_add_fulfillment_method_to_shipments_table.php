<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('fulfillment_method')
                ->default('manual')
                ->after('order_id');

            $table->index('fulfillment_method');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex([
                'shipments_fulfillment_method_index',
            ]);

            $table->dropColumn('fulfillment_method');
        });
    }
};
