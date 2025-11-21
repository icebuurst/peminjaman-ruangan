<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking ALL column collations in database...\n\n";

$columns = DB::select("
    SELECT TABLE_NAME, COLUMN_NAME, COLLATION_NAME 
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND COLLATION_NAME IS NOT NULL
    ORDER BY TABLE_NAME, COLUMN_NAME
");

$collations = [];
foreach ($columns as $col) {
    $collation = $col->COLLATION_NAME;
    if (!isset($collations[$collation])) {
        $collations[$collation] = [];
    }
    $collations[$collation][] = "{$col->TABLE_NAME}.{$col->COLUMN_NAME}";
}

foreach ($collations as $collation => $cols) {
    echo "\n=== Collation: $collation ===\n";
    foreach ($cols as $col) {
        echo "  - $col\n";
    }
}

echo "\n\n=== Checking Database Collation ===\n";
$db = DB::select("SELECT SCHEMA_NAME, DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = DATABASE()");
echo "Database: {$db[0]->SCHEMA_NAME}\n";
echo "Default Charset: {$db[0]->DEFAULT_CHARACTER_SET_NAME}\n";
echo "Default Collation: {$db[0]->DEFAULT_COLLATION_NAME}\n";
