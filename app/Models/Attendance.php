<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_user_id',
        'date',
        'check_in_time',
        'check_in_location',
        'check_out_time',
        'check_out_location',
        'type',
        'lat',
        'long'
    ];

    public function internalUser()
    {
        return $this->belongsTo(InternalUser::class, 'internal_user_id');
    }
}
