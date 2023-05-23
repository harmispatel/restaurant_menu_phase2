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

        $new_order = Order::where('shop_id',$shop_id)->where('is_new',1)->where('order_status','pending')->get();

        if(count($new_order) > 0)
        {
            foreach ($new_order as $neword)
            {
                $new_order_id = (isset($neword['id'])) ? $neword['id'] : '';
                $ord = Order::find($new_order_id);
                $ord->is_new = 0;
                $ord->update();
            }
        }

        $data['orders'] = Order::where('shop_id',$shop_id)->whereIn('order_status',['pending','accepted'])->orderBy('id','DESC')->get();
        return view('client.orders.orders',$data);
    }


    // Function for Get newly created order
    public function getNewOrders()
    {
        $html = '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $shop_settings = getClientSettings($shop_id);
        // Shop Currency
        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        // Order Settings
        $order_setting = getOrderSettings($shop_id);
        // Default Printer
        $default_printer = (isset($order_setting['default_printer']) && !empty($order_setting['default_printer'])) ? $order_setting['default_printer'] : 'Microsoft Print to PDF';
        // Printer Paper
        $printer_paper = (isset($order_setting['printer_paper']) && !empty($order_setting['printer_paper'])) ? $order_setting['printer_paper'] : 'A4';
        // Printer Tray
        $printer_tray = (isset($order_setting['printer_tray']) && !empty($order_setting['printer_tray'])) ? $order_setting['printer_tray'] : '';
        // Auto Print
        $auto_print = (isset($order_setting['auto_print']) && !empty($order_setting['auto_print'])) ? $order_setting['auto_print'] : 0;

        // Orders
        $orders = Order::where('shop_id',$shop_id)->whereIn('order_status',['pending','accepted'])->orderBy('id','DESC')->get();

        if(count($orders) > 0)
        {
            foreach($orders as $order)
            {
                $html .= '<div class="order">';
                    $html .= '<div class="order-btn d-flex align-items-center justify-content-end">';
                        $html .= '<div class="d-flex align-items-center flex-wrap">Estimated time of arrival <input type="number" name="estimated_time" id="estimated_time" value="'.$order->estimated_time.'" class="form-control mx-1 estimated_time" style="width: 100px!important" ord-id="'.$order->id.'"';
                        if($order->order_status == 'accepted')
                        {
                            $html .= 'disabled';
                        }
                        else
                        {
                            $html .= '';
                        }
                        $html .= '> Minutes.</div>';

                        if($auto_print == 0)
                        {
                            $html .= '<a class="btn btn-sm btn-primary ms-3" onclick="printReceipt('.$order->id .')"><i class="bi bi-printer"></i></a>';
                        }

                        if($order->order_status == 'pending')
                        {
                            $html .= '<a class="btn btn-sm btn-primary ms-3" onclick="acceptOrder('.$order->id.')"><i class="bi bi-check-circle" data-bs-toggle="tooltip" title="Accept"></i> Accept</a>';
                        }
                        elseif($order->order_status == 'accepted')
                        {
                            $html .= '<a class="btn btn-sm btn-success ms-3" onclick="finalizedOrder('.$order->id.')"><i class="bi bi-check-circle" data-bs-toggle="tooltip" title="Complete"></i> Finalize</a>';
                        }
                    $html .= '</div>';

                    $html .= '<div class="order-info">';
                        $html .= '<ul>';
                            $html .= '<li><strong>#'.$order->id.'</strong></li>';
                            $html .= '<li><strong>Order Date : </strong>'.date('d-m-Y h:i:s',strtotime($order->created_at)).'</li>';
                            $html .= '<li><strong>Order Type : </strong>'.$order->checkout_type.'</li>';
                            $html .= '<li><strong>Payment Method : </strong>'.$order->payment_method.'</li>';

                            if($order->checkout_type == 'takeaway')
                            {
                                $html .= '<li><strong>Customer : </strong>'.$order->firstname.' '.$order->lastname.'</li>';
                                $html .= '<li><strong>Telephone : </strong> '.$order->phone.'</li>';
                                $html .= '<li><strong>Email : </strong> '.$order->email.'</li>';
                            }
                            elseif($order->checkout_type == 'table_service')
                            {
                                $html .= '<li><strong>Table No. : </strong> '.$order->table.'</li>';
                            }
                            elseif($order->checkout_type == 'room_delivery')
                            {
                                $html .= '<li><strong>Customer : </strong>'.$order->firstname.' '.$order->lastname.'</li>';
                                $html .= '<li><strong>Room No. : </strong> '.$order->room.'</li>';
                                if(!empty($order->delivery_time ))
                                {
                                    $html .= '<li><strong>Delivery Time : </strong> '.$order->delivery_time.'</li>';
                                }
                            }
                            elseif($order->checkout_type == 'delivery')
                            {
                                $html .= '<li><strong>Customer : </strong>'.$order->firstname.' '.$order->lastname.'</li>';
                                $html .= '<li><strong>Telephone : </strong> '.$order->phone.'</li>';
                                $html .= '<li><strong>Email : </strong> '.$order->email.'</li>';
                                $html .= '<li><strong>Address : </strong> '.$order->address.'</li>';
                                $html .= '<li><strong>Floor : </strong> '.$order->floor.'</li>';
                                $html .= '<li><strong>Door Bell : </strong> '.$order->door_bell.'</li>';
                                $html .= '<li><strong>Google Map : </strong> <a href="https://maps.google.com?q='.$order->address.'" target="_blank">Address Link</a></li>';
                                $html .= '<li><strong>Comments : </strong> '.$order->instructions.'</li>';
                            }

                        $html .= '</ul>';
                    $html .= '</div>';

                    $html .= '<hr>';

                    $html .= '<div class="order-info mt-2">';
                        $html .= '<div class="row">';
                            $html .= '<div class="col-md-3">';
                                $html .= '<table class="table">';
                                    if($order->discount_per > 0)
                                    {
                                        $html .= '<tr>';
                                            $html .= '<td><b>Sub Total</b></td>';
                                            $html .= '<td class="text-end">'.$order->order_total_text.'</td>';
                                        $html .= '</tr>';

                                        $html .= '<tr>';
                                            $html .= '<td><b>Discount</b></td>';
                                            $html .= '<td class="text-end">- '.$order->discount_per.'%</td>';
                                        $html .= '</tr>';

                                        $discount_amount = ($order->order_total * $order->discount_per) / 100;
                                        $discount_amount = $order->order_total - $discount_amount;

                                        $html .= '<tr class="text-end">';
                                            $html .= '<td colspan="2"><strong>'.Currency::currency($currency)->format($discount_amount).'</strong></td>';
                                        $html .= '</tr>';
                                    }
                                    else
                                    {
                                        $html .= '<tr>';
                                            $html .= '<td><b>Total</b></td>';
                                            $html .= '<td class="text-end">'.$order->order_total_text.'</td>';
                                        $html .= '</tr>';
                                    }
                                $html .= '</table>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';

                    $html .= '<hr>';

                    $html .= '<div class="order-items">';
                        $html .= '<div class="row">';
                            if(count($order->order_items) > 0)
                            {
                                $html .= '<div class="col-md-8">';
                                    $html .= '<table class="table">';
                                        foreach ($order->order_items as $ord_item)
                                        {
                                            $sub_total = ( $ord_item['sub_total'] / $ord_item['item_qty']);
                                            $option = unserialize($ord_item['options']);

                                            $html .= '<tr>';
                                                $html .= '<td>';
                                                    $html .= '<b>'.$ord_item['item_qty'].' x '.$ord_item['item_name'].'</b>';
                                                    if(!empty($option))
                                                    {
                                                        $html .= '<br> '.implode(', ',$option);
                                                    }
                                                $html .= '</td>';
                                                $html .= '<td width="25%" class="text-end">'.Currency::currency($currency)->format($sub_total).'</td>';
                                                $html .= '<td width="25%" class="text-end">'.$ord_item['sub_total_text'].'</td>';
                                            $html .= '</tr>';
                                        }
                                    $html .= '</table>';
                                $html .= '</div>';
                            }
                        $html .= '</div>';
                    $html .= '</div>';

                $html .= '</div>';
            }
        }
        else
        {
            $html .= '<div class="row">';
                $html .= '<div class="col-md-12 text-center">';
                    $html .= '<h3>Orders Not Available</h3>';
                $html .= '</div>';
            $html .= '</div>';
        }

        return response()->json([
            'success' => 1,
            'data' => $html,
        ]);
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
        $all_data['play_sound'] = (isset($request->play_sound)) ? $request->play_sound : 0;
        $all_data['printer_paper'] = (isset($request->printer_paper)) ? $request->printer_paper : '';
        $all_data['printer_tray'] = (isset($request->printer_tray)) ? $request->printer_tray : '';
        $all_data['notification_sound'] = (isset($request->notification_sound)) ? $request->notification_sound : 'buzzer-01.mp3';

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
            $order->order_status = 'accepted';
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


    // Function for Finalized Order
    public function finalizedOrder(Request $request)
    {
        $order_id = $request->order_id;
        try
        {
            $order = Order::find($order_id);
            $order->order_status = 'completed';
            $order->update();

            return response()->json([
                'success' => 1,
                'message' => 'Order has been Completed SuccessFully...',
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


    // Function for Get Order Notification
    public function orderNotification(Request $request)
    {
        $html = '';
        $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';
        $new_order_count = Order::where('shop_id',$shop_id)->where('order_status','pending')->where('is_new',1)->count();

        if($new_order_count > 0)
        {
            $html .= 'You Have '.$new_order_count.' New Orders';
            $html .= '<a href="'.route('client.orders').'"><span class="badge rounded-pill bg-primary p-2 ms-2">View All</span></a>';
        }
        else
        {
            $html .= 'You Have 0 New Orders';
            $html .= '<a href="'.route('client.orders').'"><span class="badge rounded-pill bg-primary p-2 ms-2">View All</span></a>';
        }


        return response()->json([
            'success' => 1,
            'data' => $html,
            'count' => $new_order_count,
        ]);
    }

}
