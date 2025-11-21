<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking room photos in database...\n\n";

$rooms = DB::table('room')->select('id_room', 'nama_room', 'foto')->get();

foreach ($rooms as $room) {
    echo "ID: {$room->id_room}\n";
    echo "Nama: {$room->nama_room}\n";
    echo "Foto: " . ($room->foto ?? 'NULL') . "\n";
    
    if ($room->foto) {
        $fullPath = storage_path('app/public/' . $room->foto);
        echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        echo "Full path: {$fullPath}\n";
    }
    echo "---\n\n";
}
