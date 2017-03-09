<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionEventScheduler extends Migration
{
    /**
     * Run the migrations.
     * MySQL event handler that removes expired session; session expires after a
     * minute of creation.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE EVENT event_expired_session_removal
            ON SCHEDULE EVERY 15 SECOND
            DO
                DELETE FROM session WHERE timestamp < NOW() - INTERVAL 1 MINUTE;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP EVENT `event_expired_session_removal`');
    }
}
