<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCoupon extends Model
{
    use HasFactory;

    protected $table = 'shop_coupons';

    protected $guarded = [];
}
