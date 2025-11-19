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

        // Drop existing BEFORE triggers so we can recreate with jadwal_reguler check
        DB::unprepared('DROP TRIGGER IF EXISTS booking_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS booking_before_update');

        // Recreate BEFORE INSERT trigger with jadwal_reguler enforcement
        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_before_insert
        BEFORE INSERT ON booking
        FOR EACH ROW
        BEGIN
            DECLARE _hari VARCHAR(20);
            -- map DAYOFWEEK to Indonesian weekday names used in jadwal_reguler.hari
            SET @dow = DAYOFWEEK(NEW.tanggal_mulai);
            CASE @dow
                WHEN 1 THEN SET _hari = 'Minggu';
                WHEN 2 THEN SET _hari = 'Senin';
                WHEN 3 THEN SET _hari = 'Selasa';
                WHEN 4 THEN SET _hari = 'Rabu';
                WHEN 5 THEN SET _hari = 'Kamis';
                WHEN 6 THEN SET _hari = 'Jumat';
                WHEN 7 THEN SET _hari = 'Sabtu';
                ELSE SET _hari = NULL;
            END CASE;

            IF NEW.status = 'approved' THEN
                SET @newStart = TIMESTAMP(NEW.tanggal_mulai, NEW.jam_mulai);
                SET @newEnd = TIMESTAMP(NEW.tanggal_selesai, NEW.jam_selesai);
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NULL) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;

            -- enforce jadwal_reguler: if there's a regular schedule for same room & weekday with overlapping times => reject
            IF _hari IS NOT NULL THEN
                IF EXISTS(
                    SELECT 1 FROM jadwal_reguler jr
                    WHERE jr.id_room = NEW.id_room
                      AND jr.hari = _hari
                      AND NOT (jr.jam_selesai <= NEW.jam_mulai OR jr.jam_mulai >= NEW.jam_selesai)
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap_reguler';
                END IF;
            END IF;
        END;
        SQL);

        // Recreate BEFORE UPDATE trigger with jadwal_reguler enforcement
        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_before_update
        BEFORE UPDATE ON booking
        FOR EACH ROW
        BEGIN
            DECLARE _hari VARCHAR(20);
            SET @dow = DAYOFWEEK(NEW.tanggal_mulai);
            CASE @dow
                WHEN 1 THEN SET _hari = 'Minggu';
                WHEN 2 THEN SET _hari = 'Senin';
                WHEN 3 THEN SET _hari = 'Selasa';
                WHEN 4 THEN SET _hari = 'Rabu';
                WHEN 5 THEN SET _hari = 'Kamis';
                WHEN 6 THEN SET _hari = 'Jumat';
                WHEN 7 THEN SET _hari = 'Sabtu';
                ELSE SET _hari = NULL;
            END CASE;

            IF NEW.status = 'approved' THEN
                SET @newStart = TIMESTAMP(NEW.tanggal_mulai, NEW.jam_mulai);
                SET @newEnd = TIMESTAMP(NEW.tanggal_selesai, NEW.jam_selesai);
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NEW.id_booking) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;

            IF _hari IS NOT NULL THEN
                IF EXISTS(
                    SELECT 1 FROM jadwal_reguler jr
                    WHERE jr.id_room = NEW.id_room
                      AND jr.hari = _hari
                      AND NOT (jr.jam_selesai <= NEW.jam_mulai OR jr.jam_mulai >= NEW.jam_selesai)
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap_reguler';
                END IF;
            END IF;
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

        // Optionally, recreate previous triggers without jadwal_reguler check by calling the earlier migration SQL
        // For simplicity we'll leave them dropped in rollback scenario.
    }
};
