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
        Schema::create('estimation_room', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->nullable(); 
            $table->string('room_id')->nullable(); 
            $table->integer('estimation_id');
            $table->string('x_position')->nullable(); 
            $table->string('y_position')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimation_room');
    }
};
