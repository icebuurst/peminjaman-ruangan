<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking triggers...\n\n";

$triggers = DB::select('SHOW TRIGGERS');

foreach ($triggers as $trigger) {
    echo "Trigger: {$trigger->Trigger}\n";
    echo "Event: {$trigger->Event}\n";
    echo "Table: {$trigger->Table}\n";
    echo "Statement: " . substr($trigger->Statement, 0, 200) . "...\n";
    echo "---\n\n";
}
