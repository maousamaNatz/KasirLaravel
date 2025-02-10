<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_masakan';
    protected $fillable = ['nama_masakan', 'harga', 'status_masakan'];

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'id_masakan');
    }
}
