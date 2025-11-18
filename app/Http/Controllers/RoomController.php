<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\JadwalReguler;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::withCount('bookings')
            ->with(['bookings' => function($query) {
                // Get today's bookings
                $query->whereDate('tanggal_mulai', '<=', today())
                      ->whereDate('tanggal_selesai', '>=', today())
                      ->where('status', 'approved');
            }])
            ->orderBy('nama_room')
            ->get();
            
        // Add availability status to each room
        $rooms->each(function($room) {
            $now = now();
            $todayBookings = $room->bookings->filter(function($booking) use ($now) {
                $startTime = $booking->tanggal_mulai->format('Y-m-d') . ' ' . $booking->jam_mulai;
                $endTime = $booking->tanggal_selesai->format('Y-m-d') . ' ' . $booking->jam_selesai;
                return $now->between($startTime, $endTime);
            });
            
            if ($todayBookings->count() > 0) {
                $room->availability_status = 'busy';
                $room->availability_text = 'Sedang Digunakan';
            } elseif ($room->bookings->count() > 0) {
                $room->availability_status = 'booked';
                $room->availability_text = 'Ada Booking Hari Ini';
            } else {
                $room->availability_status = 'available';
                $room->availability_text = 'Tersedia';
            }
        });
        
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_room' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('rooms', 'public');
        }

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::with(['bookings.user', 'jadwalReguler'])->findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'nama_room' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('rooms', 'public');
        }

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus');
    }
}
