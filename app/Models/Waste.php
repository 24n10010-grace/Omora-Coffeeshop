<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    protected $table = 't_waste';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal' => 'date',
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
