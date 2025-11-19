<?php

namespace App\Http\Controllers;

use App\Models\JadwalReguler;
use App\Models\Room;
use Illuminate\Http\Request;

class JadwalRegulerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure weekdays order is Senin..Minggu, then sort by start time
        $jadwals = JadwalReguler::with('room')
            // Use FIELD to ensure weekday ordering (Senin..Minggu)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jam_mulai')
            ->get();
        return view('jadwal-reguler.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::orderBy('nama_room')->get();
        return view('jadwal-reguler.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_room' => 'required|exists:room,id_room',
            'nama_kegiatan' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
        ]);

        JadwalReguler::create($validated);

        return redirect()->route('jadwal-reguler.index')->with('success', 'Jadwal reguler berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jadwal = JadwalReguler::with('room')->findOrFail($id);
        return view('jadwal-reguler.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jadwal = JadwalReguler::findOrFail($id);
        $rooms = Room::orderBy('nama_room')->get();
        return view('jadwal-reguler.edit', compact('jadwal', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jadwal = JadwalReguler::findOrFail($id);

        $validated = $request->validate([
            'id_room' => 'required|exists:room,id_room',
            'nama_kegiatan' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal-reguler.index')->with('success', 'Jadwal reguler berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = JadwalReguler::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal-reguler.index')->with('success', 'Jadwal reguler berhasil dihapus');
    }
}
