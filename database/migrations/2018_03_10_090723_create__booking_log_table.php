<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->enum('status', ['booking', 'waiting verification', 'booked', 'canceled', 'expired']);
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
        Schema::dropIfExists('booking_log');
    }
}
