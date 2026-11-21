<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanDetail extends Model
{
    protected $table = 't_penerimaan_detail';
    protected $guarded = ['id'];
    protected $casts = [
        'qty_utuh' => 'decimal:4',
        'qty_ecer' => 'decimal:4',
        'total_ecer' => 'decimal:4',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
