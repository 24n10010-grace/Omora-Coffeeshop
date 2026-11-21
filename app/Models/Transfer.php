<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 't_transfer';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function outletAsal()
    {
        return $this->belongsTo(Outlet::class, 'outlet_asal_id');
    }

    public function outletTujuan()
    {
        return $this->belongsTo(Outlet::class, 'outlet_tujuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransferDetail::class, 'transfer_id');
    }
}
