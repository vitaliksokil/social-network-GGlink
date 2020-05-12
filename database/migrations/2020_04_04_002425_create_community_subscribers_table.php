<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunitySubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_subscribers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('community_id');
            $table->boolean('is_moderator')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_creator')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'community_id']);

        });

        Schema::table('community_subscribers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('community_subscribers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['community_id']);
        });
        Schema::dropIfExists('community_subscribers');
    }
}
