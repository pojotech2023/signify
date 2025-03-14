<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderExecutiveTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_executive_id',
        'remarks',
        'whatsapp_audio',
        'geo_latitude',
        'geo_longitude',
        'status',
    ];

    public function orderTaskAssign()
    {
        return $this->belongsTo(OrderTaskAssign::class, 'assigned_executive_id');
    }
}
