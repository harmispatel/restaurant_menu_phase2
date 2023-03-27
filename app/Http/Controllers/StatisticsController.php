<?php

namespace App\Http\Controllers;

use App\Models\CategoryVisit;
use App\Models\Clicks;
use App\Models\ItemsVisit;
use App\Models\Shop;
use App\Models\UserVisits;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function index($key="")
    {

        // Shop ID
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $date_arr = [];
        $user_visits_arr = [];
        $total_clicks_arr = [];
        $today = Carbon::now()->addDay();

        if($key == 'last_month')
        {
            $month = Carbon::now()->subMonth();
        }
        elseif($key == 'last_six_month')
        {
            $month = Carbon::now()->subMonth(6);
        }
        elseif($key == 'lifetime')
        {
            $shop_details = Shop::find($shop_id);
            $month = isset($shop_details['created_at']) ? $shop_details['created_at'] : '';
        }
        else
        {
            $month = Carbon::now()->subMonth();
        }

        $month_array = CarbonPeriod::create($month, $today);

        if(count($month_array) > 0)
        {
            foreach($month_array as $dateval)
            {
                $date_arr[] = $dateval->format('d-m-Y');
                $user_visits = UserVisits::where('shop_id',$shop_id)->whereDate('created_at','=',$dateval->format('Y-m-d'))->count();
                $user_visits_arr[$dateval->format('d-m-Y')] = $user_visits;
                $clicks = Clicks::where('shop_id',$shop_id)->whereDate('created_at','=',$dateval->format('Y-m-d'))->first();
                $total_clicks_arr[] = isset($clicks['total_clicks']) ? $clicks['total_clicks'] : '';
            };
        }

        // Most 5 Visited Category
        $data['category_visit'] = CategoryVisit::with(['category'])->where('shop_id',$shop_id)->orderByRaw("CAST(total_clicks as UNSIGNED) DESC")->limit(5)->get();

        // most visited Item
        $data['items_visit'] = ItemsVisit::with(['item'])->where('shop_id',$shop_id)->orderByRaw("CAST(total_clicks as UNSIGNED) DESC")->limit(5)->get();

        $data['current_key'] = $key;
        $data['date_array'] = $date_arr;
        $data['user_visits_array'] = $user_visits_arr;
        $data['total_clicks_array'] = $total_clicks_arr;

        return view('client.statistics.statistics',$data);
    }

}
