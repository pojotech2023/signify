<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalUser extends Model
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
}
