<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Booking;
use App\Models\JadwalReguler;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
    {
        // Redirect to dashboard if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Ambil data dari DB dengan struktur tabel baru
        $rooms   = Room::orderBy('id_room')->get(['id_room as id','nama_room','lokasi','deskripsi','kapasitas','foto']);
        $users   = User::orderBy('id')->get(['id','name','email','role']);
        $bookRaw = Booking::with(['user', 'room'])->orderBy('id_booking')->get();
        $jadwal  = JadwalReguler::with('room')->orderBy('id_reguler')->get();

        // Mapping booking dengan relasi Eloquent
        $bookings = $bookRaw->map(function($b){
            // Combine tanggal dan jam untuk format datetime
            $tanggalMulai = $b->tanggal_mulai ? $b->tanggal_mulai->format('Y-m-d') : '';
            $tanggalSelesai = $b->tanggal_selesai ? $b->tanggal_selesai->format('Y-m-d') : '';
            $jamMulai = $b->jam_mulai ?? '00:00:00';
            $jamSelesai = $b->jam_selesai ?? '00:00:00';
            
            return [
                'id'        => $b->id_booking,
                'ruangan'   => $b->room ? $b->room->nama_room : '',
                'user'      => $b->user ? $b->user->name : '',
                'email'     => $b->user ? $b->user->email : '',
                'mulai'     => $tanggalMulai ? $tanggalMulai . ' ' . substr($jamMulai, 0, 8) : '',
                'selesai'   => $tanggalSelesai ? $tanggalSelesai . ' ' . substr($jamSelesai, 0, 8) : '',
                'status'    => $b->status ?? 'pending',
                'keperluan' => $b->keperluan ?? '',
                'catatan'   => $b->catatan ?? '',
            ];
        });

        // Mapping jadwal reguler
        $jadwalReguler = $jadwal->map(function($j){
            return [
                'id'                => $j->id_reguler,
                'nama_kegiatan'     => $j->nama_kegiatan,
                'ruangan'           => $j->room ? $j->room->nama_room : '',
                'hari'              => $j->hari,
                'jam_mulai'         => $j->jam_mulai,
                'jam_selesai'       => $j->jam_selesai,
                'penanggung_jawab'  => $j->penanggung_jawab ?? '',
            ];
        });

        // Kirim ke Blade
        return view('app', [
            'seed' => [
                'rooms'          => $rooms,
                'users'          => $users,
                'bookings'       => $bookings,
                'jadwalReguler'  => $jadwalReguler,
            ]
        ]);
    }
}
