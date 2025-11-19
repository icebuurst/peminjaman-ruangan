<?php
// Demo script to show booking suggestion outputs without web UI
require __DIR__ . '/../vendor/autoload.php';

use App\Services\BookingSuggestionService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// bootstrap app
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = new BookingSuggestionService();

// Example parameters: room 1, start today, end today, 10:00-12:00
$roomId = $argv[1] ?? 1;
$startDate = $argv[2] ?? date('Y-m-d');
$endDate = $argv[3] ?? $startDate;
$jamMulai = $argv[4] ?? '10:00';
$jamSelesai = $argv[5] ?? '12:00';

$includePending = in_array('--include-pending', $argv);
// optional args: argv[6]=searchDays, argv[7]=stepMinutes
$searchDays = isset($argv[6]) && is_numeric($argv[6]) ? (int)$argv[6] : 14;
$stepMinutes = isset($argv[7]) && is_numeric($argv[7]) ? (int)$argv[7] : 30;
$alts = $s->suggest((int)$roomId, $startDate, $endDate, $jamMulai, $jamSelesai, 5, $searchDays, $stepMinutes, '08:00', '18:00', $includePending);

echo "Suggested alternatives for room {$roomId} (requested {$startDate} {$jamMulai}-{$jamSelesai}):\n";
foreach ($alts as $a) {
    echo " - {$a['date']} {$a['jam_mulai']}-{$a['jam_selesai']}\n";
}

if (empty($alts)) {
    echo "No alternatives found in the search window.\n";
}
