<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class InternalUser extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'role_id',
        'name',
        'mobile_no',
        'email_id',
        'password',
        'designation',
        'profile_image'
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function assignAdminSuperusers()
    {
        return $this->hasMany(LeadAssign::class, 'internal_user_id');
    }

    public function isAdmin()
    {
        return $this->role->role_name === 'Admin';
    }

    public function isSuperuser()
    {
        return $this->role->role_name === 'Superuser';
    }

    public function task()
    {
        return $this->hasMany(LeadTask::class, 'created_by');
    }

    public function orderAssign()
    {
        return $this->hasMany(OrderAssign::class, 'internal_user_id');
    }

    public function orderTask()
    {
        return $this->hasMany(OrderTask::class, 'created_by');
    }
    
    public function jobAssign()
    {
        return $this->hasMany(JobAssign::class, 'internal_user_id');
    }

    public function jobTask()
    {
        return $this->hasMany(JobTask::class, 'created_by');
    }

    public function deviceToken()
    {
        return $this->hasMany(DeviceToken::class, 'internal_user_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'internal_user_id');
    }

    protected $hidden = [
        'password'
    ];

}
