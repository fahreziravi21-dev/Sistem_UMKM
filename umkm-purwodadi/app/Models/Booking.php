<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_booking';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_booking',
        'id_pengguna',
        'nama_penerima',
        'no_tlp_penerima',
        'alamat_pengiriman',
        'total_harga',
        'status_booking',
        'snap_token',
        'metode_pembayaran',
        'tgl_booking',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_booking' => 'datetime',
    ];

    /**
     * Get the pengguna that owns the booking.
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Get all detail bookings for the booking.
     */
    public function detailBookings()
    {
        return $this->hasMany(DetailBooking::class, 'id_booking', 'id_booking');
    }

    /**
     * Get the pembayaran associated with the booking.
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_booking', 'id_booking');
    }

    /**
     * Generate a unique kode_booking string.
     *
     * @return string
     */
    public static function generateKodeBooking(): string
    {
        return 'BK-' . date('YmdHis');
    }
}
