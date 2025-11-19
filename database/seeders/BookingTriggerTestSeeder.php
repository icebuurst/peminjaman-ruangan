<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;

class BookingTriggerTestSeeder extends Seeder
{
    public function run()
    {
        // Ensure there's a room and user exist (assume ids 1 exist, otherwise skip)
        try {
            // Create initial pending booking (should succeed)
            $res1 = Booking::create([
                'id_room' => 1,
                'id_user' => 1,
                'keperluan' => 'Seed test 1 pending',
                'tanggal_mulai' => '2025-11-20',
                'tanggal_selesai' => '2025-11-20',
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'status' => 'pending',
            ]);
            echo "Inserted pending booking id: {$res1->id_booking}\n";
        } catch (\Exception $e) {
            echo "Insert 1 error: " . $e->getMessage() . "\n";
        }

        try {
            // Create another pending booking and then attempt to approve it (should fail due to overlap)
            $res2 = Booking::create([
                'id_room' => 1,
                'id_user' => 1,
                'keperluan' => 'Seed test 2 pending',
                'tanggal_mulai' => '2025-11-20',
                'tanggal_selesai' => '2025-11-20',
                'jam_mulai' => '11:00',
                'jam_selesai' => '13:00',
                'status' => 'pending',
            ]);
            echo "Inserted pending booking id: {$res2->id_booking}\n";

            // Now attempt to approve the second booking which should invoke trigger and fail
            $res2->status = 'approved';
            $res2->save();
            echo "Approved booking id: {$res2->id_booking}\n";
        } catch (\Exception $e) {
            echo "Approve 2 error: " . $e->getMessage() . "\n";
        }
    }
}
