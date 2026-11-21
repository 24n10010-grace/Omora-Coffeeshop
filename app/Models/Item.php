<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'm_item';
    protected $guarded = ['id'];
    protected $casts = [
        'pack_standard' => 'decimal:4',
        'stok_minimum' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function boms()
    {
        return $this->hasMany(Bom::class, 'item_id');
    }

    public function stockLedgers()
    {
        return $this->hasMany(StockLedger::class, 'item_id');
    }

    public function beginStocks()
    {
        return $this->hasMany(BeginStock::class, 'item_id');
    }
}
