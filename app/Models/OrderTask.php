<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
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

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function CreatedBy() //Order Task CreatedBy
    {
        return $this->belongsTo(InternalUser::class, 'created_by');
    }

    public function orderTaskAssign()
    {
        return $this->hasOne(OrderTaskAssign::class, 'order_task_id')->latestOfMany();
    }

}
