<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_name'
    ];

    public function internalUser(){
        return $this->hasMany(InternalUser::class, 'role_id');
    }

    public function job(){
        return $this->hasMany(Job::class, 'role_id');
    }
}
