<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadAssign extends Model
{
    use HasFactory;

    protected $fillable = ['internal_user_id', 'lead_id', 'status'];

    public function internalUser()
    {
        return $this->belongsTo(InternalUser::class, 'internal_user_id');
    }

    public function aggregatorForm()
    {
        return $this->belongsTo(AggregatorForm::class, 'lead_id');
    }
}
