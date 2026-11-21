<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'm_user';

    protected $fillable = [
        'outlet_id',
        'username',
        'email',
        'password',
        'nama_lengkap',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'user_id');
    }

    public function penerimaan()
    {
        return $this->hasMany(Penerimaan::class, 'user_id');
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'user_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
