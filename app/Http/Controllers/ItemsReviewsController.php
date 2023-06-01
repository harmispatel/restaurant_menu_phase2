<?php

namespace App\Http\Controllers;

use App\Models\ItemReview;
use Illuminate\Http\Request;

class ItemsReviewsController extends Controller
{
    function index()
    {
        $data['item_reviews'] = ItemReview::with(['item'])->orderBy('id','desc')->get();
        return view('client.reviews.item_reviews',$data);
    }
}
