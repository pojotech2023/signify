<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
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

    public function aggregatorForm()
    {
        return $this->belongsTo(AggregatorForm::class, 'lead_id');
    }

    public function CreatedBy()
    {
        return $this->belongsTo(InternalUser::class, 'created_by');
    }

    public function leadTaskAssign()
    {
        return $this->hasOne(LeadTaskAssign::class, 'task_id')->latestOfMany();
    }

}
