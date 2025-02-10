<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_order';
    protected $fillable = [
        'id_order',
        'id_masakan',
        'jumlah',
        'keterangan',
        'status_detail_order'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'id_masakan');
    }
}
