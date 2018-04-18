<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER tr_booking AFTER INSERT ON `schedule` FOR EACH ROW
            BEGIN
                INSERT INTO booking (`schedule_id`, `status`, `created_at`, `updated_at`) 
                VALUES (NEW.id, NEW.status, NEW.created_at, NEW.updated_at);
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_booking`');        
    }
}
