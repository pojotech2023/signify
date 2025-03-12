<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignExecutive extends Model
{
    use HasFactory;

    protected $fillable = [
        'executive_id',
        'task_id',
        'status'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function executive()
    {
        return $this->belongsTo(InternalUser::class, 'executive_id');
    }

    public function executiveTask()
    {
        return $this->hasOne(ExecutiveTask::class, 'assigned_executive_id');
    }
}
