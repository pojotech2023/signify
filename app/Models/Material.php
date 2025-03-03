<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'sub_category_id','material_name','material_main_img','material_sub_img'];

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
}
