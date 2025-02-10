<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_user',
        'username',
        'password',
        'id_level'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected $primaryKey = 'id_user';

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_user');
    }

    public function makanans()
    {
        return $this->hasMany(Makanan::class, 'id_user');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }
}
