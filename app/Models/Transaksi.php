<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Transaksi untuk merepresentasikan data transaksi pembayaran
 * dalam sistem kasir restoran
 */
class Transaksi extends Model
{
    use HasFactory;

    /**
     * Nama kolom primary key
     * @var string
     */
    protected $primaryKey = 'id_transaksi';

    /**
     * Field yang bisa diisi secara massal
     * @var array<string>
     */
    protected $fillable = ['id_user', 'id_order', 'tanggal', 'total_bayar'];

    /**
     * Casting tipe data untuk kolom tertentu
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'datetime',
        'total_bayar' => 'decimal:2'
    ];

    /**
     * Relasi belongsTo ke model User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi belongsTo ke model Order
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
