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


    // Destroy Item Reviews
    function destroy(Request $request)
    {
        $review_id = $request->id;
        try
        {
            ItemReview::where('id',$review_id)->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Review has been Deleted SuccessFully...',
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }
}
