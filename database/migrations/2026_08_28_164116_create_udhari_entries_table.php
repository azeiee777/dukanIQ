<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('udhari_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('party_id')->constrained('parties')->onDelete('cascade');

            // 'given'    = you extended credit to them (they now owe you more)
            // 'received' = they repaid you (what they owe you goes down)
            // 'taken'    = you took credit from them (you now owe them more)
            // 'paid'     = you repaid them (what you owe them goes down)
            $table->enum('type', ['given', 'received', 'taken', 'paid']);

            $table->decimal('amount', 15, 2);
            $table->string('note')->nullable();
            $table->date('date');

            $table->timestamps();

            $table->index(['user_id', 'party_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('udhari_entries');
    }
};
