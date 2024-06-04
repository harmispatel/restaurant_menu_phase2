<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableStaff extends Model
{
    use HasFactory;

    protected $table = 'table_staff';

    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(ShopTable::class, 'table_id');
    }
}
