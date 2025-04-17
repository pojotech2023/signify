<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable=[
        'internal_user_id',
        'device_token',
        'device_type'
    ];

    public function internalUser()
    {
        return $this->belongsTo(InternalUser::class, 'internal_user_id');
    }

}
