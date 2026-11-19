<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produks';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_produk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'merk',
        'harga',
        'stok',
        'deskripsi',
        'status',
        'gambar',
    ];

    /**
     * Get all detail bookings for the produk.
     */
    public function detailBookings()
    {
        return $this->hasMany(DetailBooking::class, 'id_produk', 'id_produk');
    }

    /**
     * Accessor: get formatted harga in Rupiah.
     *
     * @return string
     */
    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
