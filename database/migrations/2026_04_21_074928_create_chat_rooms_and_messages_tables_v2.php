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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_one_id');
            $table->unsignedBigInteger('user_two_id');
            $table->timestamps();

            $table->foreign('user_one_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id'); 
            $table->unsignedBigInteger('sender_id');    
            $table->text('body');                       
            $table->timestamps();

            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chat_rooms');
    }
};