<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // // Add room_id to the products table
        // Schema::table('products', function (Blueprint $table) {
        //     $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('products', function (Blueprint $table) {
        //     $table->dropForeign(['room_id']);
        //     $table->dropColumn('room_id');
        // });

        Schema::dropIfExists('rooms');
    }
}
