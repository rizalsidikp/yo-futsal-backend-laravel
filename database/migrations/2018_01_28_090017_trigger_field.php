<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER tr_field AFTER INSERT ON `users` FOR EACH ROW
            BEGIN
                IF NEW.scope = 'field' THEN BEGIN
                    INSERT INTO field (`user_id`,`created_at`,`updated_at`) 
                    VALUES (NEW.id,NEW.created_at,NEW.updated_at);
                END; END IF;
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
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_field`');
    }
}
