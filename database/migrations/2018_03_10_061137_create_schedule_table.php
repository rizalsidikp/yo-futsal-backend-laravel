<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('team_id');
            $table->integer('field_id');
            $table->string('opponent_id')->nullable();
            $table->boolean('open_opponent')->default(false);
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['booking', 'waiting opponent', 'match', 'canceled_user', 'canceled_field', 'expired', 'completed']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule');
    }
}
