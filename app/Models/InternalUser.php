<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'name',
        'mobile_no',
        'email_id',
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function assignAdminSuperusers()
    {
        return $this->hasMany(AssignAdminSuperuser::class, 'internal_user_id');
    }

    public function isAdmin()
    {
        return $this->role->role_name === 'Admin';
    }

    public function isSuperuser()
    {
        return $this->role->role_name === 'Superuser';
    }
}
