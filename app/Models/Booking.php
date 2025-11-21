<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    public $timestamps = true;

    protected $fillable = [
        'id_room',
        'id_user',
        'keperluan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'jumlah_peserta',
        'status',
        'catatan',
        'confirmed_at',
        'confirmation_deadline',
        'last_reminder_sent_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'confirmed_at' => 'datetime',
        'confirmation_deadline' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED_BY_USER = 'cancelled_by_user';
    const STATUS_EXPIRED = 'expired';

    /**
     * Relasi dengan user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi dengan room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }

    /**
     * Check if booking is awaiting confirmation
     */
    public function isAwaitingConfirmation()
    {
        return $this->status === self::STATUS_APPROVED 
            && is_null($this->confirmed_at)
            && !is_null($this->confirmation_deadline);
    }

    /**
     * Check if confirmation deadline has passed
     */
    public function isConfirmationExpired()
    {
        return $this->isAwaitingConfirmation() 
            && now()->isAfter($this->confirmation_deadline);
    }

    /**
     * Check if booking needs confirmation
     */
    public function needsConfirmation()
    {
        return $this->isAwaitingConfirmation() && !$this->isConfirmationExpired();
    }

    /**
     * Get remaining time for confirmation in hours
     */
    public function getConfirmationRemainingHours()
    {
        if (!$this->needsConfirmation()) {
            return 0;
        }
        
        return now()->diffInHours($this->confirmation_deadline, false);
    }
}
