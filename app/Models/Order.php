<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['lead_id', 'status'];

    public function lead() //AggregatorForm
    {
        return $this->belongsTo(AggregatorForm::class, 'lead_id');
    }

    public function orderAssign()
    {
        return $this->hasOne(OrderAssign::class, 'order_id')->latestOfMany();
    }

    public function orderTask()
    {
        return $this->hasMany(OrderTask::class, 'order_id');
    }

}
