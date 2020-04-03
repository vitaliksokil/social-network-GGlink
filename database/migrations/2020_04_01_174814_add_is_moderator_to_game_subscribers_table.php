<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsModeratorToGameSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_subscribers', function (Blueprint $table) {
            $table->boolean('is_moderator')->default(0)->after('game_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_subscribers', function (Blueprint $table) {
            $table->dropColumn('is_moderator');
        });
    }
}
