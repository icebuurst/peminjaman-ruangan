<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing collation mismatch...\n\n";

try {
    // Ubah collation tabel booking_audit
    DB::statement('ALTER TABLE booking_audit CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "✓ Successfully converted booking_audit to utf8mb4_unicode_ci\n";
    
    // Verify
    $result = DB::select("SELECT TABLE_NAME, TABLE_COLLATION FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'booking_audit'");
    echo "\nNew collation for booking_audit: " . $result[0]->TABLE_COLLATION . "\n";
    
    echo "\nCollation fix completed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
