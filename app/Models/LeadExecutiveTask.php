<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadExecutiveTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_assigned_user_id',
        'remarks',
        'whatsapp_audio',
        'address',
        'end_date_time',
        'status',
    ];

    
    public function assignedExecutive()
    {
        return $this->belongsTo(LeadTaskAssign::class, 'task_assigned_user_id');
    }

}
