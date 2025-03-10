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
        'width',
        'height',
        'unit',
        'location',
        'quantity',
        'design_service_need',
        'email_id',
        'site_image',
        'design_attachment',
        'reference_image',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function shade()
    {
        return $this->hasMany(AggregatorFormShade::class)->with('shade');
    }

    public function assignAdminSuperusers()
    {
        return $this->hasMany(AssignAdminSuperuser::class, 'user_form_id');
    }

    public function latestAssignment()
    {
        return $this->hasOne(AssignAdminSuperuser::class, 'user_form_id')->latestOfMany();
    }
}
