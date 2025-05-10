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
        Schema::create('mattress_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size_code'); // E.g., 2.5X3, 3X6, 4X6
            $table->decimal('width', 5, 2);
            $table->decimal('length', 5, 2);
            $table->decimal('height', 5, 2)->nullable();
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mattress_sizes');
    }
};
