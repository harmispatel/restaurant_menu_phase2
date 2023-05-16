<?php

namespace App\Http\Controllers;

use App\Models\DeliveryAreas;
use App\Models\Order;
use App\Models\OrderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    // Function for Display Client Orders
    public function index()
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $data['orders'] = Order::where('shop_id',$shop_id)->where('order_status','pending')->get();
        return view('client.orders.orders',$data);
    }


    // Function for Display Client Orders History
    public function ordersHistory()
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $data['orders'] = Order::where('shop_id',$shop_id)->get();
        return view('client.orders.orders_history',$data);
    }


    // function for view OrderSettings
    public function OrderSettings()
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $data['order_settings'] = getOrderSettings($shop_id);
        $data['deliveryAreas'] = DeliveryAreas::where('shop_id',$shop_id)->get();

        return view('client.orders.order_settings',$data);
    }


    // Function for Update Order Settings
    public function UpdateOrderSettings(Request $request)
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $all_data['delivery'] = (isset($request->delivery)) ? $request->delivery : 0;
        $all_data['takeaway'] = (isset($request->takeaway)) ? $request->takeaway : 0;
        $all_data['room_delivery'] = (isset($request->room_delivery)) ? $request->room_delivery : 0;
        $all_data['table_service'] = (isset($request->table_service)) ? $request->table_service : 0;
        $all_data['only_cart'] = (isset($request->only_cart)) ? $request->only_cart : 0;
        $all_data['auto_order_approval'] = (isset($request->auto_order_approval)) ? $request->auto_order_approval : 0;
        $all_data['scheduler_active'] = (isset($request->scheduler_active)) ? $request->scheduler_active : 0;
        $all_data['min_amount_for_delivery'] = (isset($request->min_amount_for_delivery)) ? $request->min_amount_for_delivery : '';
        $all_data['discount_percentage'] = (isset($request->discount_percentage)) ? $request->discount_percentage : '';
        $all_data['order_arrival_minutes'] = (isset($request->order_arrival_minutes)) ? $request->order_arrival_minutes : 30;
        $all_data['schedule_array'] = $request->schedule_array;

        try
        {
            // Insert or Update Settings
            foreach($all_data as $key => $value)
            {
                $query = OrderSetting::where('shop_id',$shop_id)->where('key',$key)->first();
                $setting_id = isset($query->id) ? $query->id : '';

                if (!empty($setting_id) || $setting_id != '')  // Update
                {
                    $settings = OrderSetting::find($setting_id);
                    $settings->value = $value;
                    $settings->update();
                }
                else // Insert
                {
                    $settings = new OrderSetting();
                    $settings->shop_id = $shop_id;
                    $settings->key = $key;
                    $settings->value = $value;
                    $settings->save();
                }
            }

            // Insert Delivery Zones Area
            $delivery_zones = (isset($request->new_coordinates) && !empty($request->new_coordinates)) ? json_decode($request->new_coordinates,true) : [];

            if(count($delivery_zones) > 0)
            {
                foreach($delivery_zones as $delivery_zone)
                {
                    $polygon = serialize($delivery_zone);

                    $delivery_area = new DeliveryAreas();
                    $delivery_area->shop_id = $shop_id;
                    $delivery_area->coordinates = $polygon;
                    $delivery_area->save();
                }
            }

            return response()->json([
                'success' => 1,
                'message' => 'Setting has been Updated SuccessFully...',
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


    // Function for Clear Delivery Range Settings
    public function clearDeliveryRangeSettings()
    {
        $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';

        DeliveryAreas::where('shop_id',$shop_id)->delete();

        return redirect()->route('order.settings')->with('success',"Setting has been Updated SuccessFully..");

    }


    // Function for Change Order Estimated Time
    public function changeOrderEstimate(Request $request)
    {
        $order_id = $request->order_id;
        $estimated_time = $request->estimate_time;
        if($estimated_time == '' || $estimated_time == 0 || $estimated_time < 0)
        {
            $estimated_time = '30';
        }

        try
        {
            $order = Order::find($order_id);
            $order->estimated_time = $estimated_time;
            $order->update();

            return response()->json([
                'success' => 1,
                'message' => 'Time has been Changed SuccessFully...',
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


    // Function for Accpeting Order
    public function acceptOrder(Request $request)
    {
        $order_id = $request->order_id;
        try
        {
            $order = Order::find($order_id);
            $order->order_status = 'completed';
            $order->update();

            return response()->json([
                'success' => 1,
                'message' => 'Order has been Accepted SuccessFully...',
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


    // Function for view Order
    public function viewOrder($order_id)
    {
        try
        {
            $order_id = decrypt($order_id);
            $data['order'] = Order::with(['order_items'])->where('id',$order_id)->first();
            return view('client.orders.order_details',$data);
        }
        catch (\Throwable $th)
        {
            return redirect()->route('client.orders')->with('error',"Internal Server Error!");
        }
    }


    // Function for Set Delivery Address in Session
    public function setDeliveryAddress(Request $request)
    {
        $lat = $request->latitude;
        $lng = $request->longitude;
        $address = $request->address;

        try
        {
            session()->put('cust_lat',$lat);
            session()->put('cust_long',$lng);
            session()->put('cust_address',$address);
            session()->save();

            return response()->json([
                'success' => 1,
                'message' => 'Address has been set successfully...',
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
