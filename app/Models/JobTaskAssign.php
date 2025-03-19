<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTaskAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_user_id',
        'job_task_id',
        'status'
    ];

    public function jobTask()
    {
        return $this->belongsTo(jobTask::class, 'job_task_id');
    }

    public function executive()
    {
        return $this->belongsTo(InternalUser::class, 'internal_user_id');
    }

    public function jobExecutiveTask()
    {
        return $this->hasOne(JobExecutiveTask::class, 'task_assigned_user_id');
    }

}
