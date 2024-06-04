<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Layout,DefaultShopLayout};
use Illuminate\Support\Facades\Auth;

class LayoutController extends Controller
{

    public function index()
    {
        $layouts = Layout::get();

        $shopId = Auth::user()->hasOneShop->shop_id;

        $active = DefaultShopLayout::where('shop_id',$shopId)->first();

        $activeLayout = isset($active) ? $active : '';

        return view('client.design.layout.layout_preview',compact('layouts','activeLayout'));
    }

    public function changeLayout(Request $request)
    {
        $shopId = Auth::user()->hasOneShop->shop_id;

        $query = DefaultShopLayout::where('shop_id',$shopId)->first();
        // dd($query);

        if (!empty($query) && $query != null) {
            $active_layout = DefaultShopLayout::find($query->id);
            $active_layout->layout_id = $request->layout_id;
            $active_layout->update();
        }else{
            $active_layout = new DefaultShopLayout();
            $active_layout->shop_id = $shopId;
            $active_layout->layout_id = $request->layout_id;
            $active_layout->save();
        }


        return response()->json([
            'success' => 1,
            'message' => 'Layout has been Activated SuccessFully...',
        ]);
    }
}
