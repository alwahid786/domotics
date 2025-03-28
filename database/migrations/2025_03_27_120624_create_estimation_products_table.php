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
        Schema::create('estimation_products', function (Blueprint $table) {
            $table->id();
            $table->integer('estimation_id');
            $table->integer('product_id');
            $table->string('product_coordinates')->nullable(); 
            $table->string('name')->nullable(); 
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimation_products');
    }
};
