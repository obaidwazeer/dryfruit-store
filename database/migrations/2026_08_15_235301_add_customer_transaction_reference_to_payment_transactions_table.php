<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('customer_transaction_reference', 150)
                ->nullable()
                ->after('transaction_reference');

            $table->timestamp('customer_submitted_at')
                ->nullable()
                ->after('initiated_at');

            $table->index('customer_transaction_reference');
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropIndex([
                'customer_transaction_reference',
            ]);

            $table->dropColumn([
                'customer_transaction_reference',
                'customer_submitted_at',
            ]);
        });
    }
};
