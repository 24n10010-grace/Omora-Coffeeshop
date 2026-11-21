<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'm_supplier';
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function penerimaan()
    {
        return $this->hasMany(Penerimaan::class, 'supplier_id');
    }
}
