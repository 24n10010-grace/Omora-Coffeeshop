<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 't_stock_opname';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class, 'stock_opname_id');
    }
}
