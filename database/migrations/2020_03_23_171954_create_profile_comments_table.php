<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('comment');

            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('writer_id');

            $table->timestamps();

            $table->index('recipient_id');
            $table->index('writer_id');
        });
        Schema::table('profile_comments',function (Blueprint $table){
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('writer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_comments', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropForeign(['writer_id']);
        });
        Schema::dropIfExists('profile_comments');
    }
}
