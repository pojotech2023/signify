<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'task_name',
        'task_priority',
        'entry_time',
        'completion_expected_by',
        'description',
        'whatsapp_audio',
        'attachments',
        'vendor_name',
        'vendor_mobile',
        'customer_name',
        'customer_mobile',
        'created_by',
        'status'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function CreatedBy() //Order Task CreatedBy
    {
        return $this->belongsTo(InternalUser::class, 'created_by');
    }

    public function jobTaskAssign()
    {
        return $this->hasOne(JobTaskAssign::class, 'job_task_id')->latestOfMany();
    }

}
