<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadTaskAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_user_id',
        'task_id',
        'status'
    ];

    public function task()
    {
        return $this->belongsTo(LeadTask::class, 'task_id');
    }

    public function executive()
    {
        return $this->belongsTo(InternalUser::class, 'internal_user_id');
    }

    public function leadExecutiveTask()
    {
        return $this->hasOne(LeadExecutiveTask::class, 'task_assigned_user_id');
    }

}
