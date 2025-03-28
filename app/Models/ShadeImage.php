<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShadeImage extends Model
{
    use HasFactory;

    protected $fillable = ['shade_id', 'shade_img'];

    public function shade()
    {
        return $this->belongsTo(Shade::class);
    }
}
