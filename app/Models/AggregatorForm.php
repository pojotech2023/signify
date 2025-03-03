<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatorForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'material_id',
        'shade_id',
        'width',
        'height',
        'unit',
        'location',
        'quantity',
        'design_service_need',
        'email_id',
        'site_image',
        'design_attachment',
        'reference_image'
    ];
}
