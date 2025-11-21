<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking table collations...\n\n";

$tables = DB::select('SELECT TABLE_NAME, TABLE_COLLATION FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()');

foreach ($tables as $table) {
    echo "Table: {$table->TABLE_NAME} -> Collation: {$table->TABLE_COLLATION}\n";
}

echo "\n\nChecking column collations for booking table...\n\n";

$columns = DB::select("SELECT COLUMN_NAME, COLLATION_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'booking' AND COLLATION_NAME IS NOT NULL");

foreach ($columns as $col) {
    echo "Column: {$col->COLUMN_NAME} -> Collation: {$col->COLLATION_NAME}\n";
}

echo "\n\nChecking column collations for room table...\n\n";

$columns = DB::select("SELECT COLUMN_NAME, COLLATION_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'room' AND COLLATION_NAME IS NOT NULL");

foreach ($columns as $col) {
    echo "Column: {$col->COLUMN_NAME} -> Collation: {$col->COLLATION_NAME}\n";
}
