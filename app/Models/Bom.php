<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    protected $table = 't_bom';
    protected $guarded = ['id'];
    protected $casts = [
        'qty_per_porsi' => 'decimal:4',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
