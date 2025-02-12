<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailOrder extends Model
{
    use HasFactory; // Trait untuk factory pattern

    /**
     * Kolom primary key tabel
     * @var string
     */
    protected $primaryKey = 'id_detail_order';

    /**
     * Field yang bisa diisi secara massal
     * @var array<string>
     */
    protected $fillable = [
        'id_order',          // ID order terkait
        'id_masakan',        // ID masakan yang dipesan
        'jumlah',            // Jumlah pesanan
        'keterangan',        // Catatan khusus pesanan
        'status_detail_order'// Status detail order (e.g. diproses, selesai)
    ];

    /**
     * Relasi belongsTo ke model Order
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    /**
     * Relasi belongsTo ke model Makanan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'id_masakan');
    }
}
