<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopRoom extends Model
{
    use HasFactory;

    protected $table = 'shop_rooms';

    protected $guarded = [];

    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'room_staff', 'shop_room_id', 'staffs_id');
    }
}
