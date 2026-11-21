<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $table = 't_stock_ledger';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal' => 'date',
        'qty_masuk' => 'decimal:4',
        'qty_keluar' => 'decimal:4',
        'saldo_ecer' => 'decimal:4',
        'created_at' => 'datetime',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
