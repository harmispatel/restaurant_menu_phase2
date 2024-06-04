<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    public function tag()
    {
        return $this->hasOne(CategoryProductTags::class,'item_id','id');
    }

    public function ratings()
    {
        return $this->hasMany(ItemReview::class,'item_id','id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_item');
    }
}
