<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $table = 't_stock_opname_detail';
    protected $guarded = ['id'];
    protected $casts = [
        'sisa_utuh' => 'decimal:4',
        'sisa_ecer' => 'decimal:4',
        'total_ecer' => 'decimal:4',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
