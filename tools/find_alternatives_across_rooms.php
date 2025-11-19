<?php
// Scan all rooms and run suggestion service to find alternatives for a requested slot
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Room;
use App\Services\BookingSuggestionService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roomIdArg = $argv[1] ?? null; // optional specific room id
$startDate = $argv[2] ?? date('Y-m-d');
$jamMulai = $argv[3] ?? '10:00';
$jamSelesai = $argv[4] ?? '12:00';
$searchDays = isset($argv[5]) ? (int)$argv[5] : 30;
$step = isset($argv[6]) ? (int)$argv[6] : 15;
$includePending = in_array('--include-pending', $argv);

$svc = new BookingSuggestionService();

$rooms = Room::orderBy('id_room')->get();

foreach ($rooms as $r) {
    if ($roomIdArg && $r->id_room != $roomIdArg) continue;
    echo "Room {$r->id_room} - {$r->nama_room}\n";
    $alts = $svc->suggest($r->id_room, $startDate, $startDate, $jamMulai, $jamSelesai, 5, $searchDays, $step, '07:00', '20:00', $includePending);
    if (empty($alts)) {
        echo "  -> No alternatives found\n\n";
    } else {
        foreach ($alts as $a) {
            echo "  - {$a['date']} {$a['jam_mulai']}-{$a['jam_selesai']}\n";
        }
        echo "\n";
    }
}

echo "Done.\n";
