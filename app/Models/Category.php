<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable=['category'];

    public function subCategories(){
        return $this->hasMany(SubCategory::class);
    }

    public function materials(){
        return $this->hasMany(Material::class);
    }

    public function shades(){
        return $this->hasMany(Shade::class);
    }

    public function aggregatorForm(){
        return $this->hasMany(AggregatorForm::class);
    }
}
