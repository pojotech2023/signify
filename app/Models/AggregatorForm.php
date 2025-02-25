<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatorForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'sub_category',
        'material_img',
        'material_name',
        'shades_img',
        'shades_name',
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
