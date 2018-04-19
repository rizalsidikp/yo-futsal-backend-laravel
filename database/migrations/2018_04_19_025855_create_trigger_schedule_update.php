<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerScheduleUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER tr_schedule_log_update BEFORE UPDATE ON `schedule` FOR EACH ROW
            BEGIN
                IF (OLD.status != New.status) THEN  
                    INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, concat('Change status from ', OLD.status ,' to ', NEW.status), NEW.created_at, NEW.updated_at);
                ELSEIF (OLD.open_opponent != NEW.open_opponent) THEN
                    INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, concat('Change open opponent from ', OLD.open_opponent ,' to ', NEW.open_opponent), NEW.created_at, NEW.updated_at);
                ELSEIF (OLD.opponent_id IS NULL and NEW.opponent_id IS NOT NULL) THEN
                    INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, concat('Change opponent to ', NEW.opponent_id), NEW.created_at, NEW.updated_at);
                ELSEIF (OLD.opponent_id IS NOT NULL and NEW.opponent_id IS NULL) THEN
                    INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, concat('Remove opponent from ', OLD.opponent_id), NEW.created_at, NEW.updated_at);
                ELSEIF (OLD.opponent_id != NEW.opponent_id) THEN
                    INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, concat('Change opponent from ', OLD.opponent_id ,' to ', NEW.opponent_id), NEW.created_at, NEW.updated_at);
                ELSEIF (OLD.deleted_at IS NULL and NEW.deleted_at IS NOT NULL) THEN
                    INSERT INTO schedule_log (`schedule_id`,`opponent_id`,`open_opponent`, `status`, `description`, `created_at`, `updated_at`) 
                    VALUES (NEW.id,NEW.opponent_id,NEW.open_opponent, NEW.status, 'Schedule deleted', NEW.created_at, NEW.updated_at);
                END IF;
                IF (OLD.open_opponent = 1 and NEW.open_opponent = 0) THEN
                    SET NEW.opponent_id = null;
                END IF;
                IF (NEW.status = 'canceled_user' or NEW.status = 'canceled_field') THEN
                    UPDATE booking set status = 'canceled' where schedule_id = NEW.id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_schedule_log_update`');      
    }
}
