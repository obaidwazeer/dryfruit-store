<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('sku')->unique();

            $table->unsignedInteger('weight_grams');

            $table->decimal('price', 12, 2);

            $table->decimal('compare_at_price', 12, 2)
                ->nullable();

            $table->unsignedInteger('stock_quantity')
                ->default(0);

            $table->unsignedInteger('low_stock_threshold')
                ->default(5);

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index([
                'product_id',
                'is_active',
            ]);

            $table->index('weight_grams');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
