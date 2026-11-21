<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'm_menu';
    protected $guarded = ['id'];
    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function boms()
    {
        return $this->hasMany(Bom::class, 'menu_id');
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class, 'menu_id');
    }
}
