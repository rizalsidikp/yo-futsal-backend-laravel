<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerBookingLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared("
        CREATE TRIGGER tr_booking_log AFTER INSERT ON `booking` FOR EACH ROW
            BEGIN
                INSERT INTO booking_log (`booking_id`,`status`, `description`, `created_at`, `updated_at`) 
                VALUES (NEW.id, NEW.status, concat('Change status to ', NEW.status), NEW.created_at, NEW.updated_at);
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
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_booking_log`');        
    }
}
