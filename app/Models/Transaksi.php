<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['id_user', 'id_order', 'tanggal', 'total_bayar'];
    protected $casts = [
        'tanggal' => 'datetime',
        'total_bayar' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
