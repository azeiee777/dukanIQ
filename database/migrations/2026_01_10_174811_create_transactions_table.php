<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Link to Shop Owner
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['sale', 'expense']);
            $table->decimal('amount', 15, 2); // Increased to 15 digits for larger sums

            // Made nullable as requested
            $table->string('category')->nullable();

            $table->string('description');
            $table->date('date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};