<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Creating approve_booking Stored Procedure ===\n\n";

try {
    // Drop existing procedure if exists
    DB::statement('DROP PROCEDURE IF EXISTS approve_booking');
    echo "✓ Dropped existing procedure (if any)\n";
    
    // Create procedure
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
            WHERE id_booking = p_booking_id AND status = 'pending';
            
            IF ROW_COUNT() = 0 THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'booking_not_found_or_not_pending';
            END IF;
        COMMIT;
    END;
SQL);
    
    echo "✓ Stored procedure 'approve_booking' created successfully\n\n";
    
    // Verify procedure exists
    $procedures = DB::select("
        SELECT ROUTINE_NAME 
        FROM information_schema.ROUTINES 
        WHERE ROUTINE_SCHEMA = DATABASE() 
        AND ROUTINE_NAME = 'approve_booking'
    ");
    
    if (count($procedures) > 0) {
        echo "✓ Verified: Procedure exists in database\n";
    } else {
        echo "✗ Warning: Procedure not found after creation\n";
    }
    
    echo "\n✅ Setup completed successfully!\n";
    echo "\nYou can now approve bookings from the web interface.\n";
    
} catch (\Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "\nIf this error persists, use Solution 2 (bypass stored procedure)\n";
}
