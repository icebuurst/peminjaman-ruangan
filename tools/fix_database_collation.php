<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing database default collation...\n\n";

try {
    $dbName = DB::getDatabaseName();
    
    // Change database default collation
    DB::statement("ALTER DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Successfully changed database default collation to utf8mb4_unicode_ci\n\n";
    
    // Verify
    $db = DB::select("SELECT SCHEMA_NAME, DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = DATABASE()");
    echo "Database: {$db[0]->SCHEMA_NAME}\n";
    echo "Default Charset: {$db[0]->DEFAULT_CHARACTER_SET_NAME}\n";
    echo "Default Collation: {$db[0]->DEFAULT_COLLATION_NAME}\n";
    
    echo "\n✅ Database collation fix completed!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
