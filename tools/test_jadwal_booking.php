<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalReguler;
use App\Models\Room;
use App\Models\User;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;

// Ensure user and room exist
$user = User::first();
if (!$user) {
    $userId = DB::table('users')->insertGetId(['name'=>'Test User','email'=>'test@example.com','password'=>bcrypt('password')]);
    $user = User::find($userId);
}
$room = Room::first();
if (!$room) {
    $roomId = DB::table('room')->insertGetId(['nama_room'=>'Test Room']);
    $room = Room::find($roomId);
}

// Create or update a JadwalReguler for Senin 10:00-12:00
$reg = JadwalReguler::updateOrCreate([
    'id_room' => $room->id_room,
    'hari' => 'Senin',
    'jam_mulai' => '10:00:00',
    'jam_selesai' => '12:00:00',
], [
    'nama_kegiatan' => 'Reguler Test',
    'penanggung_jawab' => 'Admin'
]);

echo "JadwalReguler ensured: id {$reg->id_reguler}\n";

// Simulate login
Auth::login($user);

// Prepare request data: date 2025-11-24 is Senin
$data = [
    'id_room' => $room->id_room,
    'keperluan' => 'Test booking overlapping reguler',
    'tanggal_mulai' => '2025-11-24',
    'tanggal_selesai' => '2025-11-24',
    'jam_mulai' => '10:00',
    'jam_selesai' => '12:00',
];

$request = Request::create('/bookings', 'POST', $data);
// attach session manager to request
$request->setLaravelSession(app('session')->driver());

$controller = new BookingController();
$response = $controller->store($request);

// Try to read flashed session message
$session = $request->session();
$error = $session->get('error');
$success = $session->get('success');

if ($error) {
    echo "Result: ERROR -> $error\n";
} elseif ($success) {
    echo "Result: SUCCESS -> $success\n";
} else {
    // fallback: inspect response
    if (is_object($response) && method_exists($response, 'getStatusCode')) {
        echo "Response status: " . $response->getStatusCode() . "\n";
    }
    echo "No flash message set.\n";
}

