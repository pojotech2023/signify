<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'sub_category_id',
        'material_name',
        'main_img',
        'sub_img1',
        'sub_img2',
        'sub_img3',
        'sub_img4',
        'video'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function shades()
    {
        return $this->hasMany(Shade::class);
    }

    public function aggregatorForm(){
        return $this->hasMany(AggregatorForm::class);
    }
}
