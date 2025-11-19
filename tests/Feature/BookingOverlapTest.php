<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;

class BookingOverlapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite extension not available; enable it to run DB tests locally.');
        }
    }

    public function test_overlapping_booking_is_rejected()
    {
        // create user and room
        $user = User::factory()->create(['role' => 'peminjam']);
        $room = Room::factory()->create();

        // existing booking (2025-11-20 10:00 - 12:00)
        Booking::create([
            'id_room' => $room->id_room,
            'id_user' => $user->id,
            'keperluan' => 'Existing',
            'tanggal_mulai' => '2025-11-20',
            'tanggal_selesai' => '2025-11-20',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'status' => 'approved',
        ]);

        $this->actingAs($user);

        // attempt overlapping booking (2025-11-20 11:00 - 13:00)
        $response = $this->post(route('bookings.store'), [
            'id_room' => $room->id_room,
            'keperluan' => 'Overlap test',
            'tanggal_mulai' => '2025-11-20',
            'tanggal_selesai' => '2025-11-20',
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('booking', 1);
    }

    public function test_non_overlapping_booking_is_allowed()
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $room = Room::factory()->create();

        // existing booking (2025-11-20 10:00 - 12:00)
        Booking::create([
            'id_room' => $room->id_room,
            'id_user' => $user->id,
            'keperluan' => 'Existing',
            'tanggal_mulai' => '2025-11-20',
            'tanggal_selesai' => '2025-11-20',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'status' => 'approved',
        ]);

        $this->actingAs($user);

        // attempt non-overlapping booking (2025-11-20 12:00 - 14:00) - edge: starts exactly at existing end
        $response = $this->post(route('bookings.store'), [
            'id_room' => $room->id_room,
            'keperluan' => 'Non-overlap test',
            'tanggal_mulai' => '2025-11-20',
            'tanggal_selesai' => '2025-11-20',
            'jam_mulai' => '12:00',
            'jam_selesai' => '14:00',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('booking', 2);
    }
}
