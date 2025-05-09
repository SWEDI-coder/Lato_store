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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id');
            $table->foreignId('item_id');
            $table->foreignId('part_id')->nullable();
            $table->integer('quantity');
            $table->decimal('purchase_price', 25, 2);
            $table->decimal('discount', 25, 2)->nullable();
            $table->date('expire_date')->nullable();
            $table->timestamps();

            $table->index('item_id');
            $table->index('expire_date');
            $table->index(['item_id', 'expire_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
