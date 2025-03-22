<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'role_id',
        'status'
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function jobAssign()
    {
        return $this->hasOne(JobAssign::class, 'job_id')->latestOfMany();
    }

    public function jobTask()
    {
        return $this->hasMany(JobTask::class, 'job_id');
    }

}
