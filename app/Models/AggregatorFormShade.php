<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatorFormShade extends Model
{
    use HasFactory;

    protected $fillable = ['aggregator_form_id', 'shade_id', 'selected_img'];

    public function aggregatorForm()
    {
        return $this->belongsTo(AggregatorForm::class);
    }
    public function shade()
    {
        return $this->belongsTo(Shade::class);
    }
}
