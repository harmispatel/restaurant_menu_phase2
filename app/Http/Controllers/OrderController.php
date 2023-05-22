<?php

namespace App\Http\Controllers;

use App\Models\DeliveryAreas;
use App\Models\Order;
use App\Models\OrderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;

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
        $all_data['default_printer'] = (isset($request->default_printer)) ? $request->default_printer : '';
        $all_data['receipt_intro'] = $request->receipt_intro;
        $all_data['auto_print'] = (isset($request->auto_print)) ? $request->auto_print : 0;
        $all_data['printer_paper'] = (isset($request->printer_paper)) ? $request->printer_paper : '';
        $all_data['printer_tray'] = (isset($request->printer_tray)) ? $request->printer_tray : '';

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


    // Function for set Printer JS License Key
    public function setPrinterLicense()
    {
        $license_owner = 'Dimitris Bourlos - 1 WebApp Lic - 1 WebServer Lic';
        $license_key  = '661C6658D5FC2787F94AC3E96C33BBE59C5FC29D';

        //DO NOT MODIFY THE FOLLOWING CODE
        $timestamp = request()->query('timestamp');
        $license_hash = hash('sha256', $license_key . $timestamp, false);
        $resp = $license_owner . '|' . $license_hash;

        return response($resp)->header('Content-Type', 'text/plain');
    }


    // Function for Get Order Receipt
    public function getOrderReceipt(Request $request)
    {
        $order_id = $request->order_id;
        $html = '';

        try
        {
            $order = Order::with(['order_items','shop'])->where('id',$order_id)->first();
            $shop_id = (isset($order->shop['id'])) ? $order->shop['id'] : '';

            $shop_settings = getClientSettings($shop_id);

            $order_setting = getOrderSettings($shop_id);
            $receipt_intro = (isset($order_setting['receipt_intro']) && !empty($order_setting['receipt_intro'])) ? $order_setting['receipt_intro'] : 'INVOICE';

            // Shop Currency
            $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

            $order_date = (isset($order->created_at)) ? $order->created_at : '';
            $payment_method = (isset($order->payment_method)) ? str_replace('_',' ',$order->payment_method) : '';
            $checkout_type = (isset($order->checkout_type)) ? $order->checkout_type : '';
            $customer = $order->firstname." ".$order->lastname;
            $phone = (isset($order->phone)) ? $order->phone : '';
            $email = (isset($order->email)) ? $order->email : '';
            $address = (isset($order->address)) ? $order->address : '';
            $floor = (isset($order->floor)) ? $order->floor : '';
            $table_no = (isset($order->table)) ? $order->table : '';
            $room_no = (isset($order->room)) ? $order->room : '';
            $delivery_time = (isset($order->delivery_time)) ? $order->delivery_time : '';
            $door_bell = (isset($order->door_bell)) ? $order->door_bell : '';
            $items = (isset($order->order_items)) ? $order->order_items : [];
            $order_total_text = (isset($order->order_total_text)) ? $order->order_total_text : '';

            $html .= '<div class="row">';
                $html .= '<div class="col-md-12">';
                    $html .= '<div class="card">';
                        $html .= '<div class="card-body">';
                            $html .= '<div class="row mb-3">';
                                $html .= '<div class="col-md-12 text-center">';
                                    $html .= '<h3>'.$receipt_intro.' - #'.$order_id.'</h3>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-5">';
                                    $html .= '<ul class="p-0 m-0 list-unstyled">';
                                        if($checkout_type == 'takeaway' || $checkout_type == 'delivery')
                                        {
                                            $html .= '<li><b>Customer : </b> '.$customer.'</li>';
                                            $html .= '<li><b>Phone : </b> '.$phone.'</li>';
                                            $html .= '<li><b>Email ID : </b> '.$email.'</li>';
                                        }
                                        if($checkout_type == 'delivery')
                                        {
                                            $html .= '<li><b>Bell : </b> '.$door_bell.'</li>';
                                            $html .= '<li><b>Floor No. : </b> '.$floor.'</li>';
                                            $html .= '<li><b>Address : </b> '.$address.'</li>';
                                        }
                                        if($checkout_type == 'room_delivery')
                                        {
                                            $html .= '<li><b>Customer : </b> '.$customer.'</li>';
                                            $html .= '<li><b>Room No. : </b> '.$room_no.'</li>';
                                            $html .= '<li><b>Delivery Time : </b> '.$delivery_time.'</li>';
                                        }
                                    $html .= '</ul>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-3">';
                                $html .= '</div>';
                                $html .= '<div class="col-md-4 text-end">';
                                    $html .= '<ul class="p-0 m-0 list-unstyled">';
                                        $html .= '<li><b>Order Date : </b> '.date('d-m-Y h:i:s',strtotime($order_date)).'</li>';
                                        $html .= '<li><b>Payment Method : </b> '.ucfirst($payment_method).'</li>';
                                        $html .= '<li><b>Checkout Type : </b> '.ucfirst(str_replace('_',' ',$checkout_type)).'</li>';
                                        if($checkout_type == 'table_service')
                                        {
                                            $html .= '<li><b>Table No : </b> '.$table_no.'</li>';
                                        }
                                    $html .= '</ul>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="row">';
                                $html .= '<div class="col-md-12">';
                                    $html .= '<table class="table table-bordered">';
                                        $html .= '<thead>';
                                            $html .= '<tr><th>Item Name</th><th width="10%">Qty.</th><th width="15%" class="text-end">Amount</th></tr>';
                                        $html .= '</thead>';
                                        $html .= '<tbody>';
                                            if(count($items) > 0)
                                            {
                                                foreach($items as $item)
                                                {
                                                    $item_name = (isset($item['item_name'])) ? $item['item_name'] : '';
                                                    $item_qty = (isset($item['item_qty'])) ? $item['item_qty'] : 0;
                                                    $sub_total_text = (isset($item['sub_total_text'])) ? $item['sub_total_text'] : 0;
                                                    $option = unserialize($item['options']);

                                                    $html .= '<tr>';
                                                        $html .= '<td><strong>'.$item_name.'</strong>';
                                                        if(!empty($option))
                                                        {
                                                            $html .= '<br>'.implode(', ',$option);
                                                        }
                                                        $html .= '</td>';
                                                        $html .= '<td>'.$item_qty.'</td>';
                                                        $html .= '<td class="text-end">'.$sub_total_text.'</td>';
                                                    $html .= '</tr>';
                                                }
                                            }
                                        $html .= '</tbody>';
                                    $html .= '</table>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-9 mt-2">';
                                $html .= '</div>';
                                $html .= '<div class="col-md-3 mt-2">';
                                    $html .= '<table class="table">';
                                        if($order->discount_per > 0)
                                        {
                                            $discount_amount = ($order->order_total * $order->discount_per) / 100;
                                            $discount_amount = $order->order_total - $discount_amount;
                                            $discount_amount = Currency::currency($currency)->format($discount_amount);

                                            $html .= '<tr>';
                                                $html .= '<td><strong>Sub Total : </strong></td><td class="text-end">'.$order_total_text.'</td>';
                                            $html .= '</tr>';
                                            $html .= '<tr>';
                                                $html .= '<td><strong>Discount : </strong></td><td class="text-end">-'.$order->discount_per.'%</td>';
                                            $html .= '</tr>';
                                            $html .= '<tr>';
                                                $html .= '<td><strong>Total : </strong></td><td class="text-end">'.$discount_amount.'</td>';
                                            $html .= '</tr>';
                                        }
                                    $html .= '</table>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';

            return response()->json([
                'success' => 1,
                'message' => "Receipt Generated",
                'data' => $html,
            ]);

        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }

}
