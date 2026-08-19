<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('payer_name', 255)
                ->nullable()
                ->after('amount');

            $table->decimal('submitted_amount', 12, 2)
                ->nullable()
                ->after('payer_name');

            $table->string('proof_path', 500)
                ->nullable()
                ->after('submitted_amount');

            $table->timestamp('submitted_at')
                ->nullable()
                ->after('initiated_at');

            $table->text('verification_note')
                ->nullable()
                ->after('response_message');
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payer_name',
                'submitted_amount',
                'proof_path',
                'submitted_at',
                'verification_note',
            ]);
        });
    }
};
