<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function categoryImages()
    {
        return $this->hasMany(CategoryImages::class,'category_id','id');
    }

    public function items()
    {
        return $this->belongsToMany(Items::class,'category_item');
    }
}
