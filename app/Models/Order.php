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
        'total_harga'
    ];

    // Tambahkan properti dates untuk casting
    protected $dates = ['tanggal'];

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
