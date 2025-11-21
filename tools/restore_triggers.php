<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Restoring complete triggers with correct collation...\n\n";

try {
    // Drop existing triggers
    DB::statement("DROP TRIGGER IF EXISTS booking_before_insert");
    DB::statement("DROP TRIGGER IF EXISTS booking_before_update");
    
    // Create booking_before_insert with complete logic and explicit collation
    DB::unprepared("
        CREATE TRIGGER booking_before_insert BEFORE INSERT ON booking
        FOR EACH ROW
        BEGIN
            DECLARE _hari VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            
            -- Map DAYOFWEEK to Indonesian weekday names
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

            -- Check booking conflicts only if status is 'approved'
            IF NEW.status = 'approved' THEN
                SET @newStart = TIMESTAMP(NEW.tanggal_mulai, NEW.jam_mulai);
                SET @newEnd = TIMESTAMP(NEW.tanggal_selesai, NEW.jam_selesai);
                IF booking_conflicts(NEW.id_room, @newStart, @newEnd, NULL) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap';
                END IF;
            END IF;

            -- Enforce jadwal_reguler: reject if overlaps with regular schedule
            IF _hari IS NOT NULL THEN
                IF EXISTS(
                    SELECT 1 FROM jadwal_reguler jr
                    WHERE jr.id_room = NEW.id_room
                      AND jr.hari COLLATE utf8mb4_unicode_ci = _hari COLLATE utf8mb4_unicode_ci
                      AND NOT (jr.jam_selesai <= NEW.jam_mulai OR jr.jam_mulai >= NEW.jam_selesai)
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap_reguler';
                END IF;
            END IF;
        END
    ");
    
    echo "✓ booking_before_insert trigger created\n";
    
    // Create booking_before_update with complete logic and explicit collation
    DB::unprepared("
        CREATE TRIGGER booking_before_update BEFORE UPDATE ON booking
        FOR EACH ROW
        BEGIN
            DECLARE _hari VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            
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
                      AND jr.hari COLLATE utf8mb4_unicode_ci = _hari COLLATE utf8mb4_unicode_ci
                      AND NOT (jr.jam_selesai <= NEW.jam_mulai OR jr.jam_mulai >= NEW.jam_selesai)
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'overlap_reguler';
                END IF;
            END IF;
        END
    ");
    
    echo "✓ booking_before_update trigger created\n";
    
    echo "\n✅ All triggers restored with correct collation!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
