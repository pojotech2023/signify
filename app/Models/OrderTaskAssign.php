<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTaskAssign extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'internal_user_id',
        'order_task_id',
        'status'
    ];

    public function orderTask()
    {
        return $this->belongsTo(OrderTask::class, 'order_task_id');
    }

    public function executive()
    {
        return $this->belongsTo(InternalUser::class, 'internal_user_id');
    }

    public function orderExecutiveTask()
    {
        return $this->hasOne(OrderExecutiveTask::class, 'task_assigned_user_id');
    }
}
