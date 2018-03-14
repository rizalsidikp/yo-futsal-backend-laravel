<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Auth;

class TriggerTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = Auth::user();        
        DB::unprepared("
        CREATE TRIGGER tr_team AFTER INSERT ON `team` FOR EACH ROW
            BEGIN
                INSERT INTO detail_team (`team_id`,`user_id`,`owner`, `created_at`, `updated_at`) 
                VALUES (NEW.id,NEW.user_id,true,NEW.created_at,NEW.updated_at);
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
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_team`');
    }
}
