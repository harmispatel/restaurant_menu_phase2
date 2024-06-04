<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    use HasFactory;


    protected $table = 'service_reviews';

    protected $guarded = [];

    public function  serviceName() {

        return $this->hasOne(ShopRateServies::class, 'id', 'servies_id');
    }
}
