<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    protected $table = 't_transfer_detail';
    protected $guarded = ['id'];
    protected $casts = [
        'qty_ecer' => 'decimal:4',
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
