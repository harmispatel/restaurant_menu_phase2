<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomStaff extends Model
{
    use HasFactory;

    protected $table = 'room_staff';

    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(ShopRoom::class, 'room_id');
    }
}
