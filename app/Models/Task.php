<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'created_by',
        'task_priority',
        'entry_time',
        'delivery_needed_by',
        'description',
        'whatsapp_audio',
        'attachments',
        'vendor_name',
        'vendor_mobile',
        'customer_name',
        'customer_mobile',
        'start_date',
        'end_date',
        'whatsapp_message',
    ];

    public function aggregatorForm()
    {
        return $this->belongsTo(AggregatorForm::class, 'lead_id');
    }

    public function CreatedBy()
    {
        return $this->belongsTo(InternalUser::class, 'created_by');
    }

    public function assignExecutive()
    {
        return $this->hasOne(AssignExecutive::class, 'task_id')->latestOfMany();
    }
}
