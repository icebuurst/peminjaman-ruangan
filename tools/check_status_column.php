<?php
/**
 * Check booking table status column
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking booking.status column ===\n\n";

try {
    $result = DB::select("SHOW COLUMNS FROM booking WHERE Field = 'status'");
    
    if (!empty($result)) {
        $column = $result[0];
        echo "Field: {$column->Field}\n";
        echo "Type: {$column->Type}\n";
        echo "Null: {$column->Null}\n";
        echo "Default: {$column->Default}\n\n";
        
        // Check if it's ENUM
        if (stripos($column->Type, 'enum') !== false) {
            echo "⚠️  Status column is ENUM type!\n";
            echo "Current allowed values: {$column->Type}\n\n";
            echo "We need to add new status values: confirmed, cancelled_by_user, expired\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
