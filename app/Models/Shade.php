<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shade extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'material_id',
        'shade_name',
        'shade_img1',
        'shade_img2',
        'shade_img3',
        'shade_img4'
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

    public function aggregatorFormShades()
    {
        return $this->hasMany(AggregatorFormShade::class);
    }

    public function shadeImage()
    {
        return $this->hasMany(ShadeImage::class);
    }
}
