<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('member_id')->unique();
            $table->boolean('is_joined')->default(0);
            $table->timestamps();

            $table->index('room_id');
            $table->index('member_id');
        });
        Schema::table('room_members', function (Blueprint $table) {
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_members', function (Blueprint $table) {
            $table->dropForeign('room_id');
            $table->dropForeign('member_id');
        });
        Schema::dropIfExists('room_members');
    }
}
