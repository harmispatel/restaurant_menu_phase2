<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallWaiter extends Model
{
    use HasFactory;

    protected $table = 'call_waiter';

    protected $guarded = [];

    function room()
    {
        return $this->hasOne(ShopRoom::class, 'id', 'room_or_table_no');
    }

    function table()
    {
        return $this->hasOne(ShopTable::class, 'id', 'room_or_table_no');
    }
}
