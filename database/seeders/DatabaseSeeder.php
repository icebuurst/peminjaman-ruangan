<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\JadwalReguler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@smkn1bantul.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'identity' => 'ADM001',
        ]);

        // Petugas
        $petugas = User::create([
            'name' => 'Petugas Ruangan',
            'email' => 'petugas@smkn1bantul.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'identity' => 'PTG001',
        ]);

        // Peminjam
        $peminjam = User::create([
            'name' => 'Peminjam System',
            'email' => 'peminjam@smkn1bantul.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'peminjam',
            'identity' => 'PMJ001',
        ]);

        $siswa = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.siswa@smkn1bantul.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'peminjam',
            'identity' => '2024001',
        ]);

        // Rooms
        $lab1 = Room::create([
            'nama_room' => 'Lab Komputer 1',
            'lokasi' => 'Gedung A Lt. 2',
            'deskripsi' => 'Lab dengan 40 PC',
            'kapasitas' => 40,
        ]);

        $lab2 = Room::create([
            'nama_room' => 'Lab Komputer 2',
            'lokasi' => 'Gedung A Lt. 3',
            'deskripsi' => 'Lab multimedia',
            'kapasitas' => 30,
        ]);

        // Bookings
        Booking::create([
            'id_room' => $lab1->id_room,
            'id_user' => $peminjam->id,
            'keperluan' => 'Test Booking 1',
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(1),
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'jumlah_peserta' => 30,
            'status' => 'pending',
        ]);

        Booking::create([
            'id_room' => $lab2->id_room,
            'id_user' => $siswa->id,
            'keperluan' => 'Test Booking 2',
            'tanggal_mulai' => now()->addDays(2),
            'tanggal_selesai' => now()->addDays(2),
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'jumlah_peserta' => 25,
            'status' => 'approved',
        ]);

        // Jadwal Reguler
        JadwalReguler::create([
            'id_room' => $lab1->id_room,
            'nama_kegiatan' => 'Praktikum RPL Kelas XII',
            'hari' => 'Senin',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '10:00:00',
            'penanggung_jawab' => 'Guru RPL',
        ]);
    }
}
