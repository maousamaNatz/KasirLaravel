<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Level untuk merepresentasikan data tingkat pengguna (user levels)
 * dalam aplikasi Kasir Restoran
 */
class Level extends Model
{
    use HasFactory;

    // Menentukan nama kolom primary key
    protected $primaryKey = 'id_level';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['nama_level'];

    /**
     * Mendefinisikan relasi one-to-many dengan model User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * Relasi ini menunjukkan bahwa satu level bisa dimiliki oleh banyak user.
     * Parameter pertama: Model tujuan (User)
     * Parameter kedua: Foreign key di tabel users yang merujuk ke level
     * Parameter ketiga: Primary key lokal di tabel levels
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_level', 'id_level');
    }
}
