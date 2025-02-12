<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Makanan untuk merepresentasikan data menu makanan
 * dalam sistem kasir restoran
 */
class Makanan extends Model
{
    use HasFactory;

    // Menentukan nama kolom primary key
    protected $primaryKey = 'id_masakan';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['nama_masakan', 'harga', 'status_masakan'];

    /**
     * Mendefinisikan relasi one-to-many dengan model DetailOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * Relasi ini menunjukkan bahwa satu makanan bisa muncul di banyak detail order.
     * Parameter pertama: Model tujuan (DetailOrder)
     * Parameter kedua: Foreign key di tabel detail_orders yang merujuk ke makanan
     */

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'id_masakan');
    }
}
