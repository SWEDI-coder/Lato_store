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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->date('purchase_date');
            $table->foreignId('part_id')->nullable();
            $table->decimal('total_amount', 25, 2);           // Increased to handle trillion-level values
            $table->decimal('total_discount', 25, 2)->nullable(); // Increased to handle trillion-level values
            $table->decimal('dept', 25, 2)->nullable();       // Increased to handle trillion-level values
            $table->decimal('paid', 25, 2)->nullable();       // Increased to handle trillion-level values
            $table->string('status')->default('Unpaid');

            $table->foreignId('user_id'); // who made the purchase
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};