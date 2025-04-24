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
            $table->string('reference_no')->unique()->nullable();
            $table->string('person_name')->nullable();
            $table->foreignId('sale_id')->nullable();
            $table->foreignId('purchase_id')->nullable();
            $table->foreignId('part_id')->nullable();
            $table->enum('type', ['Payment', 'Receipt'])->nullable();
            $table->string('method')->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->string('journal_memo')->nullable();
            $table->date('transaction_date')->nullable();
            $table->decimal('dept_paid', 10, 2)->nullable();
            $table->decimal('dept_remain', 10, 2)->nullable();
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
