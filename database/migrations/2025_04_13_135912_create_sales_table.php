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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->date('sale_date');
            $table->foreignId('part_id')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('total_discount', 8, 2)->nullable();
            $table->decimal('dept', 8, 2)->nullable();
            $table->decimal('paid', 8, 2)->nullable();
            $table->enum('status', ['Paid', 'Partial paid', 'Unpaid', 'Draft', 'Cancelled', 'Pending'])->default('Unpaid');

            $table->string('efd_number')->nullable();
            $table->string('z_number')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('barcode_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
