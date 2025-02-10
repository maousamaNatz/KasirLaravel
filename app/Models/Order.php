<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order';
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

    // Tambahkan casting untuk tanggal
    protected $casts = [
        'tanggal' => 'datetime',
        'total_harga' => 'decimal:2',
        'uang_bayar' => 'decimal:2',
        'uang_kembali' => 'decimal:2'
    ];

    public function getStatusPembayaranLabelAttribute()
    {
        return [
            'belum_bayar' => 'Belum Bayar',
            'kurang' => 'Kurang',
            'lunas' => 'Lunas'
        ][$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    public function getMetodePembayaranLabelAttribute()
    {
        return [
            'tunai' => 'Tunai',
            'debit' => 'Kartu Debit',
            'kredit' => 'Kartu Kredit',
            'qris' => 'QRIS'
        ][$this->metode_pembayaran] ?? $this->metode_pembayaran;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'id_order');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_order');
    }
}
