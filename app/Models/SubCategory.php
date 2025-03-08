<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable=['sub_category','category_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function materials(){
        return $this->hasMany(Material::class);
    }
    public function aggregatorForm(){
        return $this->hasMany(AggregatorForm::class);
    }

}
