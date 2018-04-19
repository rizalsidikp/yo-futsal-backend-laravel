<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('team', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('detail_team', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('team')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('schedule', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('team')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('field')->onDelete('cascade');
            $table->foreign('opponent_id')->references('id')->on('team')->onDelete('cascade');
        });
        Schema::table('schedule_log', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
            $table->foreign('opponent_id')->references('id')->on('team')->onDelete('cascade');
        });
        Schema::table('booking', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
        });
        Schema::table('booking_log', function (Blueprint $table) {
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
        });
        Schema::table('opponents', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('team')->onDelete('cascade');
            $table->foreign('opponent_id')->references('id')->on('team')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('team', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('detail_team', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['team_id']);
        });
        Schema::table('schedule', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['field_id']);
            $table->dropForeign(['opponent_id']);
        });
        Schema::table('schedule_log', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['opponent_id']);
        });
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
        });
        Schema::table('booking_log', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
        });
        Schema::table('opponents', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['team_id']);
            $table->dropForeign(['opponent_id']);
        });
    }
}
