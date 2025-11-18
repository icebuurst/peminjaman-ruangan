<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalReguler extends Model
{
    protected $table = 'jadwal_reguler';
    protected $primaryKey = 'id_reguler';
    public $timestamps = false;

    protected $fillable = [
        'id_room',
        'nama_kegiatan',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'penanggung_jawab',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    /**
     * Relasi dengan room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }
}
