<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking and recreating triggers with correct collation...\n\n";

try {
    // Get current trigger definition
    $triggers = DB::select("SHOW CREATE TRIGGER booking_before_insert");
    
    echo "Current trigger:\n";
    echo $triggers[0]->{'SQL Original Statement'} . "\n\n";
    
    // Drop and recreate trigger with explicit COLLATE
    echo "Dropping old trigger...\n";
    DB::statement("DROP TRIGGER IF EXISTS booking_before_insert");
    
    echo "Creating new trigger with explicit collation...\n";
    DB::unprepared("
        CREATE TRIGGER booking_before_insert BEFORE INSERT ON booking
        FOR EACH ROW
        BEGIN
            DECLARE _hari VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
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
            END CASE;

            -- Check if this booking overlaps with any jadwal_reguler for the same room on matching hari
            IF EXISTS (
                SELECT 1 FROM jadwal_reguler
                WHERE id_room = NEW.id_room
                  AND hari COLLATE utf8mb4_unicode_ci = _hari
                  AND NOT (jam_selesai <= NEW.jam_mulai OR jam_mulai >= NEW.jam_selesai)
            ) THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'overlap_with_reguler';
            END IF;
        END
    ");
    
    echo "✓ Trigger recreated successfully!\n\n";
    
    // Update trigger
    echo "Updating booking_before_update trigger...\n";
    DB::statement("DROP TRIGGER IF EXISTS booking_before_update");
    
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
            END CASE;

            IF EXISTS (
                SELECT 1 FROM jadwal_reguler
                WHERE id_room = NEW.id_room
                  AND hari COLLATE utf8mb4_unicode_ci = _hari
                  AND NOT (jam_selesai <= NEW.jam_mulai OR jam_mulai >= NEW.jam_selesai)
            ) THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'overlap_with_reguler';
            END IF;
        END
    ");
    
    echo "✓ Update trigger recreated successfully!\n\n";
    
    echo "✅ All triggers fixed!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
