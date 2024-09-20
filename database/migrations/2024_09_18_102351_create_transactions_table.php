<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to the user who made the transaction
            $table->string('type');  // e.g., "credit" or "debit"
            $table->text('description');  // Details of the transaction
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');  // Transaction status
            $table->decimal('amount', 10, 2);  // Transaction amount
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
