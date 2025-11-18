<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';
    protected $primaryKey = 'id_room';
    public $timestamps = false;

    protected $fillable = [
        'nama_room',
        'lokasi',
        'deskripsi',
        'kapasitas',
        'foto'
    ];

    /**
     * Relasi dengan booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_room', 'id_room');
    }

    /**
     * Relasi dengan jadwal reguler
     */
    public function jadwalReguler()
    {
        return $this->hasMany(JadwalReguler::class, 'id_room', 'id_room');
    }
}
