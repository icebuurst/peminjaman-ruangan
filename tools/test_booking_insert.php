<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Testing booking insert...\n\n";

try {
    // Test insert a booking
    $result = DB::table('booking')->insert([
        'id_room' => 1,
        'id_user' => 3,
        'keperluan' => 'Test booking collation fix',
        'tanggal_mulai' => '2025-11-30',
        'tanggal_selesai' => '2025-11-30',
        'jam_mulai' => '15:00:00',
        'jam_selesai' => '16:00:00',
        'jumlah_peserta' => 10,
        'catatan' => 'Testing',
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✅ Test booking inserted successfully!\n";
    echo "Result: " . ($result ? 'Success' : 'Failed') . "\n\n";
    
    // Get the inserted booking
    $booking = DB::table('booking')
        ->where('keperluan', 'Test booking collation fix')
        ->first();
    
    if ($booking) {
        echo "Inserted booking ID: {$booking->id_booking}\n";
        echo "Room: {$booking->id_room}\n";
        echo "Status: {$booking->status}\n";
        
        // Clean up - delete test booking
        DB::table('booking')->where('id_booking', $booking->id_booking)->delete();
        echo "\n✓ Test booking cleaned up\n";
    }
    
    echo "\n✅ All tests passed! Booking creation is working now.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
