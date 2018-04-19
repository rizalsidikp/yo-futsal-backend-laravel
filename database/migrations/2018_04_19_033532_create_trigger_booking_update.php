<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerBookingUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER tr_booking_log_update AFTER UPDATE ON `booking` FOR EACH ROW
            BEGIN
                IF (OLD.status != NEW.status) THEN
                    INSERT INTO booking_log (`booking_id`,`status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id, NEW.status, concat('Change status from ', OLD.status, ' to ', NEW.status), NEW.created_at, NEW.updated_at);
                END IF;
                IF (OLD.photo IS NULL and NEW.photo IS NOT NULL) THEN
                    INSERT INTO booking_log (`booking_id`,`status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id, NEW.status, concat('User upload transfer evidence'), NEW.created_at, NEW.updated_at);
                END IF;
                IF NEW.status = 'expired' THEN
                    UPDATE schedule set status = 'expired' where id = NEW.schedule_id;
                END IF;
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
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_booking_log_update`');
    }
}
