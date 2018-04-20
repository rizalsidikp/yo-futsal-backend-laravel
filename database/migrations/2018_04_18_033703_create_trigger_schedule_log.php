<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerScheduleLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER tr_schedule_log AFTER INSERT ON `schedule` FOR EACH ROW
            BEGIN
                INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, concat('Change status to ', NEW.status), NEW.created_at, NEW.updated_at);
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
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_schedule_log`');        
    }
}
