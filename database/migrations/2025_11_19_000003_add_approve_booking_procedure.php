<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = config('database.default');
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        // Drop if exists then create stored procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS approve_booking');

        DB::unprepared(<<<'SQL'
        CREATE PROCEDURE approve_booking(IN p_booking_id BIGINT)
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                ROLLBACK;
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'approve_failed';
            END;

            START TRANSACTION;
                UPDATE booking
                SET status = 'approved', updated_at = NOW()
                WHERE id_booking = p_booking_id;
            COMMIT;
        END;
        SQL);
    }

    public function down(): void
    {
        $driver = config('database.default');
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS approve_booking');
    }
};
