<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopCoupon;
use App\Http\Requests\ShopCouponRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    //
    public function index()
    {
        $coupons = ShopCoupon::where('shop_id',Auth::user()->hasOneShop->shop['id'])->get();
        return view('client.coupons.coupons',compact('coupons'));
    }

    public function insert()
    {
        return view('client.coupons.new_coupons');
    }

    public function store(ShopCouponRequest $request)
    {
        try {
            $data = $request->except('_token');
            $data['start_date'] = Carbon::now();
            ShopCoupon::create($data);
            return redirect()->route('coupons')->with('success','Coupon has been Inserted SuccessFully....');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('coupons')->with('error','Internal Server Error');
        }

    }

    public function changeStatus(Request $request)
    {
            // Service ID & Status

            $couponId = $request->id;
            $status = $request->status;

            try {
                $coupon = ShopCoupon::find($couponId);
                $coupon->status = $status;
                $coupon->save();


                return response()->json([
                    'success' => 1,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => 0,
                ]);
            }
    }

    public function edit($id)
    {
        $coupon = ShopCoupon::where('id', $id)->first();
        return view('client.coupons.edit_coupons', compact('coupon'));
    }

    public function update(ShopCouponRequest $request)
    {
        try {
            $data = $request->except('_token','id');
            $data['start_date'] = Carbon::now();
            ShopCoupon::find($request->id)->update($data);
            return redirect()->route('coupons')->with('success','Coupon has been Updated SuccessFully....');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('coupons')->with('error','Internal Server Error');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        try {
            ShopCoupon::where('id',$id)->delete();

            return response()->json([
                'success' => 1,
                'message' => "Coupon has been Removed SuccessFully..",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }



}
