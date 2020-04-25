<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_ships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->index('sender_id');
            $table->index('receiver_id');

            $table->unique(['sender_id', 'receiver_id']);
        });
        Schema::table('friend_ships', function (Blueprint $table) {
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friend_ships', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
        });
        Schema::dropIfExists('friend_ships');
    }
}
