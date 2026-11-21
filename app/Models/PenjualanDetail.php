<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $table = 't_penjualan_detail';
    protected $guarded = ['id'];
    protected $casts = [
        'qty' => 'decimal:4',
        'harga_satuan' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
