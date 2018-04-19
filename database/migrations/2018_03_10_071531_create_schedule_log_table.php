<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_id')->length(10)->unsigned();
            $table->string('opponent_id', 10)->nullable();
            $table->boolean('open_opponent');
            $table->enum('status', ['booking', 'waiting opponent', 'match', 'canceled_user', 'canceled_field', 'expired', 'completed']);            
            $table->text('description');            
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
        Schema::dropIfExists('schedule_log');
    }
}
