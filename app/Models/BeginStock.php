<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeginStock extends Model
{
    protected $table = 'r_begin_stock';
    protected $guarded = ['id'];
    protected $casts = [
        'qty_ecer' => 'decimal:4',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
