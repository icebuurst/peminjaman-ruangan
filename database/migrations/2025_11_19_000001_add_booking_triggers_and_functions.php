<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration adds a stored function and triggers for MySQL.
        // Only run if the current connection driver is mysql.
        $driver = config('database.default');
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        // Create a helper function to detect conflicts.
        DB::unprepared(<<<'SQL'
        DROP FUNCTION IF EXISTS booking_conflicts;
        SQL);

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
              AND NOT (CONCAT(tanggal_selesai, ' ', jam_selesai, ':00') <= p_start OR CONCAT(tanggal_mulai, ' ', jam_mulai, ':00') >= p_end);
            RETURN cnt;
        END;
        SQL);

        // Create audit table
        DB::unprepared(<<<'SQL'
        CREATE TABLE IF NOT EXISTS booking_audit (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            id_booking BIGINT NULL,
            action VARCHAR(20) NOT NULL,
            data JSON NULL,
            performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        SQL);

        // BEFORE INSERT trigger: prevent inserting an approved booking that conflicts
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS booking_before_insert;
        SQL);

        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_before_insert
        BEFORE INSERT ON booking
        FOR EACH ROW
        BEGIN
            IF NEW.status = 'approved' THEN
                SET @newStart = CONCAT(NEW.tanggal_mulai, ' ', NEW.jam_mulai, ':00');
                SET @newEnd = CONCAT(NEW.tanggal_selesai, ' ', NEW.jam_selesai, ':00');
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NULL) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;
        END;
        SQL);

        // BEFORE UPDATE trigger: prevent updating to approved if conflicts exist
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS booking_before_update;
        SQL);

        DB::unprepared(<<<'SQL'
        CREATE TRIGGER booking_before_update
        BEFORE UPDATE ON booking
        FOR EACH ROW
        BEGIN
            IF NEW.status = 'approved' THEN
                SET @newStart = CONCAT(NEW.tanggal_mulai, ' ', NEW.jam_mulai, ':00');
                SET @newEnd = CONCAT(NEW.tanggal_selesai, ' ', NEW.jam_selesai, ':00');
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NEW.id_booking) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;
        END;
        SQL);

        // AFTER INSERT/UPDATE trigger: write an audit row
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS booking_after_write;
        SQL);

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

        // Note: separate trigger for update to store action 'update'
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS booking_after_update;
        SQL);

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

    /**
     * Reverse the migrations.
     */
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
        DB::unprepared('DROP TABLE IF EXISTS booking_audit');
    }
};
