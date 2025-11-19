<?php
// Diagnostic script: show jadwal_reguler + bookings for a room in a date window
require __DIR__ . '/../vendor/autoload.php';

use App\Models\JadwalReguler;
use App\Models\Booking;
use Carbon\Carbon;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roomId = $argv[1] ?? 1;
$startDate = $argv[2] ?? date('Y-m-d');
$days = isset($argv[3]) ? (int)$argv[3] : 14;
$endDate = Carbon::parse($startDate)->addDays($days)->format('Y-m-d');

echo "Diagnostic for room {$roomId} from {$startDate} to {$endDate}\n\n";

// Jadwal reguler
$regs = JadwalReguler::where('id_room', $roomId)->get();
if ($regs->isEmpty()) {
    echo "No jadwal_reguler entries for room {$roomId}\n\n";
} else {
    echo "jadwal_reguler:\n";
    foreach ($regs as $r) {
        echo sprintf(" - %s %s-%s (id: %s)\n", $r->hari, substr($r->jam_mulai,0,5), substr($r->jam_selesai,0,5), $r->id_jadwal_reguler);
    }
    echo "\n";
}

// Bookings in window
$start = Carbon::parse($startDate);
$end = Carbon::parse($endDate);
$bookings = Booking::where('id_room', $roomId)
    ->where(function($q) use ($start, $end) {
        $q->whereBetween('tanggal_mulai', [$start->format('Y-m-d'), $end->format('Y-m-d')])
          ->orWhereBetween('tanggal_selesai', [$start->format('Y-m-d'), $end->format('Y-m-d')])
          ->orWhere(function($q2) use ($start, $end) {
              $q2->where('tanggal_mulai', '<=', $start->format('Y-m-d'))
                 ->where('tanggal_selesai', '>=', $end->format('Y-m-d'));
          });
    })->orderBy('tanggal_mulai')->get();

if ($bookings->isEmpty()) {
    echo "No bookings (approved/pending/all status) found in window for room {$roomId}\n";
} else {
    echo "Bookings in window:\n";
    foreach ($bookings as $b) {
        echo sprintf(" - id:%s status:%s %s %s-%s (user:%s)\n", $b->id_booking, $b->status, $b->tanggal_mulai, substr($b->jam_mulai,0,5), substr($b->jam_selesai,0,5), $b->id_user);
    }
}

echo "\nDone.\n";
