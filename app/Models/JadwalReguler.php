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
    // these columns are TIME in the database (HH:MM:SS)
    // casting them to datetime caused Carbon to add a date part,
    // so substr() returned the year. Keep them as strings so
    // existing views using substr(..., 0, 5) work correctly.
    'jam_mulai' => 'string',
    'jam_selesai' => 'string',
    ];

    /**
     * Relasi dengan room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }
}
