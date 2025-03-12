<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveTask extends Model
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

    public function assignedExecutive()
    {
        return $this->belongsTo(AssignExecutive::class, 'assigned_executive_id');
    }
}
