<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTable extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'table_staff', 'shop_table_id', 'staffs_id');
    }
}
