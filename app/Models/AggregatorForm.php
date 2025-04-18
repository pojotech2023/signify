<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatorForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        'mobile_no',
        'how_heard',
        'remarks',
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
        return $this->hasMany(LeadAssign::class, 'lead_id');
    }

    public function latestAssignment() //assign_admin_superuser
    {
        return $this->hasOne(LeadAssign::class, 'lead_id')->latestOfMany();
    }

    public function task()
    {
        return $this->hasMany(LeadTask::class, 'lead_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'lead_id');
    }
}
