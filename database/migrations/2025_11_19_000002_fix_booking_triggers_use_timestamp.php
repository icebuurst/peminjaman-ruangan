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

        // Drop existing triggers/function
        DB::unprepared('DROP TRIGGER IF EXISTS booking_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_before_update');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_after_write');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_after_update');
        DB::unprepared('DROP FUNCTION IF EXISTS booking_conflicts');

        // Recreate function using TIMESTAMP(date, time)
        DB::unprepared(<<<'SQL'
        CREATE FUNCTION booking_conflicts(p_room BIGINT, p_start DATETIME, p_end DATETIME, p_exclude_id BIGINT)
        RETURNS INT DETERMINISTIC
        BEGIN
            DECLARE cnt INT DEFAULT 0;
            SELECT COUNT(*) INTO cnt
            FROM booking
            WHERE id_room = p_room
              AND status != 'cancelled'
              AND (p_exclude_id IS NULL OR id_booking != p_exclude_id)
              AND NOT (TIMESTAMP(tanggal_selesai, jam_selesai) <= p_start OR TIMESTAMP(tanggal_mulai, jam_mulai) >= p_end);
            RETURN cnt;
        END;
        SQL);

        // Recreate BEFORE INSERT trigger
        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_before_insert
        BEFORE INSERT ON booking
        FOR EACH ROW
        BEGIN
            IF NEW.status = 'approved' THEN
                SET @newStart = TIMESTAMP(NEW.tanggal_mulai, NEW.jam_mulai);
                SET @newEnd = TIMESTAMP(NEW.tanggal_selesai, NEW.jam_selesai);
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NULL) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;
        END;
        SQL);

        // Recreate BEFORE UPDATE trigger
        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_before_update
        BEFORE UPDATE ON booking
        FOR EACH ROW
        BEGIN
            IF NEW.status = 'approved' THEN
                SET @newStart = TIMESTAMP(NEW.tanggal_mulai, NEW.jam_mulai);
                SET @newEnd = TIMESTAMP(NEW.tanggal_selesai, NEW.jam_selesai);
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NEW.id_booking) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;
        END;
        SQL);

        // Recreate AFTER INSERT trigger
        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_after_write
        AFTER INSERT ON booking
        FOR EACH ROW
        BEGIN
            INSERT INTO booking_audit (id_booking, action, data) VALUES (NEW.id_booking, 'insert', JSON_OBJECT(
                'id_room', NEW.id_room,
                'id_user', NEW.id_user,
                'tanggal_mulai', NEW.tanggal_mulai,
                'tanggal_selesai', NEW.tanggal_selesai,
                'jam_mulai', NEW.jam_mulai,
                'jam_selesai', NEW.jam_selesai,
                'status', NEW.status
            ));
        END;
        SQL);

        // Recreate AFTER UPDATE trigger
        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_after_update
        AFTER UPDATE ON booking
        FOR EACH ROW
        BEGIN
            INSERT INTO booking_audit (id_booking, action, data) VALUES (NEW.id_booking, 'update', JSON_OBJECT(
                'id_room', NEW.id_room,
                'id_user', NEW.id_user,
                'tanggal_mulai', NEW.tanggal_mulai,
                'tanggal_selesai', NEW.tanggal_selesai,
                'jam_mulai', NEW.jam_mulai,
                'jam_selesai', NEW.jam_selesai,
                'status', NEW.status
            ));
        END;
        SQL);
    }

    public function down(): void
    {
        $driver = config('database.default');
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS booking_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_before_update');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_after_write');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_after_update');
        DB::unprepared('DROP FUNCTION IF EXISTS booking_conflicts');
    }
};
