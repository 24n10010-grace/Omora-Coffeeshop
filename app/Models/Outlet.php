<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $table = 'm_outlet';
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'outlet_id');
    }

    public function stockLedgers()
    {
        return $this->hasMany(StockLedger::class, 'outlet_id');
    }

    public function beginStocks()
    {
        return $this->hasMany(BeginStock::class, 'outlet_id');
    }
}
