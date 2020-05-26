<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedTinyInteger('count_of_members');
            $table->unsignedBigInteger('creator_id')->unique();
            $table->unsignedBigInteger('game_id');
            $table->timestamps();

            $table->index('creator_id');
            $table->index('game_id');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['game_id']);
        });

        Schema::dropIfExists('rooms');
    }
}
