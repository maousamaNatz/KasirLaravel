<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Order untuk merepresentasikan transaksi pemesanan
 * dalam sistem kasir restoran
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Nama kolom primary key
     * @var string
     */
    protected $primaryKey = 'id_order';

    /**
     * Field yang bisa diisi secara massal
     * @var array<string>
     */
    protected $fillable = [
        'no_meja',
        'tanggal',
        'id_user',
        'keterangan',
        'status_order',
        'total_harga',
        'uang_bayar',
        'uang_kembali',
        'metode_pembayaran',
        'status_pembayaran'
    ];

    /**
     * Casting tipe data untuk kolom tertentu
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'datetime',
        'total_harga' => 'decimal:2',
        'uang_bayar' => 'decimal:2',
        'uang_kembali' => 'decimal:2'
    ];

    /**
     * Mengembalikan label status pembayaran dalam bentuk yang lebih readable
     * @return string
     */
    public function getStatusPembayaranLabelAttribute()
    {
        return [
            'belum_bayar' => 'Belum Bayar',
            'kurang' => 'Kurang',
            'lunas' => 'Lunas'
        ][$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    /**
     * Mengembalikan label metode pembayaran dalam bentuk yang lebih readable
     * @return string
     */
    public function getMetodePembayaranLabelAttribute()
    {
        return [
            'tunai' => 'Tunai',
            'debit' => 'Kartu Debit',
            'kredit' => 'Kartu Kredit',
            'qris' => 'QRIS'
        ][$this->metode_pembayaran] ?? $this->metode_pembayaran;
    }

    /**
     * Relasi belongsTo ke model User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi hasMany ke model DetailOrder
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'id_order');
    }

    /**
     * Relasi hasOne ke model Transaksi
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_order');
    }
}
