<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // 'purchase' (stock in), 'sale' (stock out, linked to a transaction),
            // 'adjustment' (manual correction, can be positive or negative)
            $table->enum('type', ['purchase', 'sale', 'adjustment']);

            // Signed: positive increases quantity, negative decreases it.
            $table->integer('quantity');

            // Cost per unit at time of movement (purchases record the cost paid;
            // sales/adjustments record the product's cost_price at that time for
            // accurate historical margin calculation).
            $table->decimal('unit_cost', 15, 2)->nullable();

            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');

            $table->string('note')->nullable();
            $table->date('date');

            $table->timestamps();

            $table->index(['user_id', 'product_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
