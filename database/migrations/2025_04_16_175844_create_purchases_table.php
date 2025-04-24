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
            $table->decimal('total_amount');
            $table->decimal('total_discount')->nullable();
            $table->decimal('dept')->nullable();
            $table->decimal('paid')->nullable();
            $table->enum('status', ['Paid', 'Partial paid', 'Unpaid', 'Draft', 'Cancelled', 'Pending'])->default('Unpaid');
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
