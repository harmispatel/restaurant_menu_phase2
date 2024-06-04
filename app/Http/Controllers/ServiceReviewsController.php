<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceReview;
use Illuminate\Support\Facades\Auth;



class ServiceReviewsController extends Controller
{
    //

    public function index()
    {
        $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';
        $uuids = ServiceReview::with('serviceName')->orderBy('id','desc')->where('shop_id',$shop_id)->get();
        $uuids = collect($uuids);
        $data['shop_reviews'] = $uuids->unique('uuid');

        return view('client.reviews.service_reviews',$data);
    }

    // Destroy Item Reviews
    public function destroy(Request $request)
    {
        $review_id = $request->id;

        try
        {
            ServiceReview::where('uuid',$review_id)->delete();

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

    public function view($uuid)
    {
        $uuids = ServiceReview::with('serviceName')->where('uuid',$uuid)->get();
        $uuids = collect($uuids);
        $data['service_review'] = $uuids->unique('uuid');
        $data['reviews'] = ServiceReview::with('serviceName')->where('uuid',$uuid)->get();;

        return view('client.reviews.service_reviews_view',$data);

    }
}
