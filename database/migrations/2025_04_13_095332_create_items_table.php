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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->string('description')->nullable();
            $table->decimal('sale_price', 25, 2)->nullable();
            $table->enum('status', ['Available', 'Not Available', 'Expired', 'Damage', 'Sold Out', 'Inactive', 'Active'])->nullable();

            $table->foreignId('brand_id')->nullable();
            $table->foreignId('mattress_type_id')->nullable();
            $table->foreignId('mattress_size_id')->nullable();
            $table->boolean('is_mattress')->default(false);
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('barcode')->nullable();
            $table->string('color')->nullable();
            $table->string('warranty_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
