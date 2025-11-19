<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$database = config('database.connections.mysql.database');

$functions = DB::select("SHOW FUNCTION STATUS WHERE Db = ?", [$database]);
$procedures = DB::select("SHOW PROCEDURE STATUS WHERE Db = ?", [$database]);
$triggers = DB::select("SHOW TRIGGERS FROM `{$database}`");

echo "Functions:\n";
print_r($functions);

echo "Procedures:\n";
print_r($procedures);

echo "Triggers:\n";
print_r($triggers);
