<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

// insert pending booking
$id = DB::table('booking')->insertGetId([
    'id_room' => 1,
    'id_user' => 1,
    'keperluan' => 'no conflict',
    'tanggal_mulai' => '2025-11-21',
    'tanggal_selesai' => '2025-11-21',
    'jam_mulai' => '09:00',
    'jam_selesai' => '10:00',
    'status' => 'pending',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]);

echo "Inserted id: $id\n";

try {
    DB::statement('CALL approve_booking(?)', [$id]);
    echo "Approved id: $id\n";
} catch (\Exception $e) {
    echo "Approve error: " . $e->getMessage() . "\n";
}
