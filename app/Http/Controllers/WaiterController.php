<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ShopRoom, ShopTable, CallWaiter, UserShop, ClientSettings};
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    //
    public function callWiter(Request $request)
    {
        try {
            $client_settings = getClientSettings($request->shop_id);
            $table_enable_status = (isset($client_settings['table_enable_status']) && !empty($client_settings['table_enable_status'])) ? $client_settings['table_enable_status'] : 0;
            $room_enable_status = (isset($client_settings['room_enable_status']) && !empty($client_settings['room_enable_status'])) ? $client_settings['room_enable_status'] : 0;

            $rooms = ShopRoom::where('shop_id',$request->shop_id)->get();
            $tables = ShopTable::where('shop_id',$request->shop_id)->get();
            $html = '';
            if (!$tables->isEmpty() || !$rooms->isEmpty()){
                $html .='<div class="modal-body">';
                    $html .='<div class="call_waiter_title">';
                        $html .='<h3>'. __('Call the Waiter').'</h3>';
                            $html .='<p>'. __('One of our representatives will serve you!') .'</p>';
                    $html .='</div>';
                    $html .='<form action="javascript:void(0)" method="POST" id="callWaiterForm" class="m-0">';
                    $html .= csrf_field();
                    $html .='<div class="call_waiter_info">';
                    $html .='<input type="hidden" name="shop_id" id="shop_id" value="'.$request->shop_id.'">';
                        $html .='<div class="form-group">';

                            if($table_enable_status == 1 && $room_enable_status == 1){
                                $html .= '<select name="location" id="location" class="form-select" onchange="changeSerive(this.value)">';
                                    $html .= '<option value="">'.__('Tell us What you Need?').'</option>';
                                    $html .= '<option value="0">'.__('Table Service').'</option>';
                                    $html .= '<option value="1">'. __('Room Service').'</option>';
                                $html .='</select>';
                            }elseif($table_enable_status == 1){
                                $html .= '<input type="hidden" name="location" id="location" class="form-select" value="0">';
                            }elseif($room_enable_status == 1){
                                $html .= '<input type="hidden" name="location" id="location" class="form-select" value="1">';
                            }

                        $html .='</div>';

                        if($table_enable_status == 1 && $room_enable_status == 1){
                            $html .='<div class="form-group room-dropdown d-none">';
                                    $html .='<select name="room" id="room" class="form-select">';
                                    $html .= '<option value="">'. __('Room Νumber').'</option>';
                                    if(count($rooms) > 0){
                                        foreach($rooms as $room){
                                            $html .='<option value="'.$room->id.'">';
                                            $html .=  $room->room_no;
                                            $html .='</option>';
                                        }
                                    }
                                    $html .='</select>';
                            $html .='</div>';
    
                            $html .='<div class="form-group table-dropdown d-none">';
                                    $html .='<select name="table" id="table" class="form-select">';
                                    $html .= '<option value="">'. __('Τable Νumber').'</option>';
                                    if(count($tables) > 0){
                                        foreach($tables as $table){
                                            $html .='<option  value="'.$table->id.'">';
                                            $html .= $table->table_name;
                                            $html .='</option>';
                                        }
                                    }
                                    $html .='</select>';
                            $html .='</div>';
                        }elseif($table_enable_status == 1){
                            $html .='<div class="form-group table-dropdown">';
                                    $html .='<select name="table" id="table" class="form-select">';
                                    $html .= '<option value="">'. __('Τable Νumber').'</option>';
                                    if(count($tables) > 0){
                                        foreach($tables as $table){
                                            $html .='<option  value="'.$table->id.'">';
                                            $html .= $table->table_name;
                                            $html .='</option>';
                                        }
                                    }
                                    $html .='</select>';
                            $html .='</div>';
                        }elseif($room_enable_status == 1){
                            $html .='<div class="form-group room-dropdown">';
                                    $html .='<select name="room" id="room" class="form-select">';
                                    $html .= '<option value="">'. __('Room Νumber').'</option>';
                                    if(count($rooms) > 0){
                                        foreach($rooms as $room){
                                            $html .='<option value="'.$room->id.'">';
                                            $html .=  $room->room_no;
                                            $html .='</option>';
                                        }
                                    }
                                    $html .='</select>';
                            $html .='</div>';
                        }

                        $html .='<div class="form-group">';
                            $html .='<input type="text" name="name" class="form-control" placeholder="'.__('You Name').'">';
                        $html .='</div>';
                        $html .='<div class="row justify-content-center">';
                            $html .='<div class="col-12">';
                                $html .='<div class="waiter_call">';
                                $html .='<input name="order" id="check2" value="1" type="checkbox">';
                                    $html .='<label class="waiter_call_box" for="check2">';
                                        $html .='<i class="fa-regular fa-file-lines me-2"></i>';
                                            $html .='<span class="text-center">'. __('Order').'</span>';
                                    $html .='</label>';
                                $html .='</div>';
                            $html .='</div>';
                            $html .='<div class="col-12">';
                                $html .='<div class="waiter_call">';
                                $html .='<input name="water" id="check3" value="1" type="checkbox">';
                                    $html .='<label class="waiter_call_box" for="check3">';
                                        $html .='<i class="fa-solid fa-glass-water me-2"></i>';
                                            $html .='<span class="text-center">'. __('Water').'</span>';
                                    $html .='</label>';
                                $html .='</div>';
                            $html .='</div>';
                            $html .='<div class="col-12">';
                                $html .='<div class="waiter_call">';
                                    $html .='<input name="pay_bill" id="check4" value="1" type="checkbox">';
                                    $html .='<label class="waiter_call_box" for="check4">';
                                        $html .='<i class="fa-regular fa-dollar-sign me-2"></i>';
                                            $html .='<span class="text-center">'. __('Bill Payment').'</span>';
                                    $html .='</label>';
                                $html .='</div>';
                            $html .='</div>';
                            $html .='<div class="col-12">';
                                $html .='<div class="waiter_call">';
                                    $html .='<input name="pay_with_bill" id="check5" value="1" type="checkbox">';
                                    $html .='<label class="waiter_call_box" for="check5">';
                                        $html .='<i class="fa-solid fa-credit-card me-2"></i>';
                                            $html .='<span class="text-center">'. __('Payment by Card').'</span>';
                                    $html .='</label>';
                                $html .='</div>';
                            $html .='</div>';
                            $html .='<div class="col-12">';
                                $html .='<div class="waiter_call">';
                                    $html .='<input name="other" id="check6" value="1" type="checkbox" onchange="toggleDescribeUs($(this).prop(\'checked\'))">';
                                    $html .='<label class="waiter_call_box" for="check6">';
                                        $html .='<i class="fa-solid fa-circle-plus me-2"></i>';
                                            $html .='<span class="text-center">'. __('Other').'</span>';
                                    $html .='</label>';
                                $html .='</div>';
                            $html .='</div>';
                        $html .='</div>';
                        $html .='<div class="form-group">';
                            $html .='<textarea name="message" class="form-control describe_to_us d-none" placeholder="'.__('Describe to us').'"></textarea>';
                        $html .='</div>';
                    $html .='</div>';
                    $html .='</div>';
                    $html .='<div class="modal-footer">';
                        $html .='<div class="add_to_cart_box w-100">';
                         $html .='<div class="row justify-content-center">';
                                    $html .='<div class="col-6">';
                                        $html .='<a class="btn call_waiter_submit_btn btn-success w-100" onclick="submitCallWaiter()">'.__('send').'</a>';
                                    $html .='</div>';
                         $html .='</div>';
                        $html .='</div>';
                        $html .='</form>';
                    $html .='</div>';
            }else{
                return response()->json([
                    'success' => 0,
                    'message' => 'Table Or Room not Permission....',
                    'data'    => $html,
                ]);
            }

            return response()->json([
                'success' => 1,
                'message' => 'Details has been Fetched SuccessFully...',
                'data'    => $html,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function sendCallWaiter(Request $request)
    {

        $request->validate([
            'location' => 'required',
            'table' => $request->filled('location') && $request->location == 0 ? 'required' : '',
            'room' => $request->filled('location') && $request->location == 1 ? 'required' : '',
        ]);

        if(!isset($request->order) && !isset($request->water) && !isset($request->pay_bill) && !isset($request->pay_with_bill) && !isset($request->other)){
            $request->validate([
                'option' => 'required',
            ],[
                'option.required'=>"Please Select Atleast One Option",
            ]);
        }

        try {


            $input = $request->except('_token','room','table');
            $input['room_or_table_no'] =  $request->location == 0 ? $request->table : $request->room;
            $waiter = CallWaiter::create($input);

            $fromEmail = UserShop::where('shop_id',$waiter->shop_id)->first();
            $from = $fromEmail->user->email;


            if($request->location == 0){
                $tables = ShopTable::where('id',$request->table)->first();
                $tableNumber = $tables->table_name;
                $staffIds = $tables->staffs()->pluck('staffs.email')->toArray();
            }else{
                $rooms = ShopRoom::where('id',$request->room)->first();
                $roomNumber = $rooms->room_no;

                $staffIds = $rooms->staffs()->pluck('staffs.email')->toArray();
            }

            if(count($staffIds) > 0){
                foreach($staffIds as $email){
                    $to = $email;
                    $subject = 'New Notification for waiter';
                    $message = '';
                    $message .= '<div>';
                        $message .= '<table style="width:100%; border:1px solid gray;border-collapse: collapse;">';
                            $message .= '<tbody style="font-weight: 700!important;">';
                                $message .= '<tr>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">Location: </td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">' . (($waiter->location == 0) ? 'Table Service' : 'Room Service') . '</td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">Number: </td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">' . (($waiter->location == 0) ? $tableNumber : $roomNumber) . '</td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">Name: </td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">' . (($waiter->name) ? $waiter->name : '') . '</td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">Message: </td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">' . (($waiter->message) ? $waiter->message : '') . '</td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">Services : </td>';
                                $message .= '<td style="padding:10px; border-bottom:1px solid gray">';
                                $message .= (($waiter->order == 1) ? 'Order, ' : '');
                                $message .= (($waiter->water == 1) ? 'Water, ' : '');
                                $message .= (($waiter->pay_bill == 1) ? 'Pay Bill, ' : '');
                                $message .= (($waiter->pay_bill == 1) ? 'Pay With Card, ' : '');
                                $message .= (($waiter->other == 1) ? 'other' : '');
                                $message = rtrim($message, ', '); // Remove trailing comma and space if any
                                $message .= '</td>';
                                $message .= '</tr>';
                            $message .= '</tbody>';
                        $message .='</table>';
                    $message .= '</div>';

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $headers .= 'From: <'.$from.'>' . "\r\n";

                    // mail($to,$subject,$message,$headers);

                }
            }

            return response()->json([
                'success' => 1,
                'message' => 'Your Request  has been Submitted SuccessFully...',
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function showAllCallWaiter()
    {
        try {
            $html = '';
                 $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

            $unreadcount = CallWaiter::where('shop_id',$shop_id)->where('read',0)->count();

            if($unreadcount > 0)
            {
                $html .= 'You Have '.$unreadcount.' New Notification Call Waiter';
                $html .= '<a href="'.route('list.call.waiter').'"><span class="badge rounded-pill bg-primary p-2 ms-2">View All</span></a>';
            }
            else
            {
                $html .= 'You Have 0 New Notification Call Waiter';
                $html .= '<a href="'.route('list.call.waiter').'"><span class="badge rounded-pill bg-primary p-2 ms-2">View All</span></a>';
            }

            return response()->json([
                'success' => 1,
                'data' => $html,
                'count' => $unreadcount,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function listCallWaiter(Request $request)
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

         $call_waiters = CallWaiter::where('shop_id',$shop_id)->with(['room', 'table'])->latest()->get();
       return view('client.waiter.waiter',compact('call_waiters'));
    }


    public function showCallWaiter($id)
    {
        try {
            $call_waiter = CallWaiter::where('id',$id)->first();


           return view('client.waiter.show_waiter',compact('call_waiter'));
        } catch (\Throwable $th) {
            //throw $th;
           return redirect()->route('client.dashboard')->with('error','Internal Server Error');


        }
    }

    public function deleteCallWaiter(Request $request)
    {
           $id = $request->id;

        try {

           $callWaiter = CallWaiter::findOrFail($id);
           $callWaiter->delete();

            return response()->json([
                'success' => 1,
                'message' => "Waiter Call has been Removed SuccessFully..",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }

    public function onOffCallWaiter(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try {
            $query = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','waiter_call_status')->first();
            $setting_id = isset($query->id) ? $query->id : '';

            if(!empty($setting_id) || $setting_id != '')
            {
                $settings = ClientSettings::find($setting_id);
                    $settings->value = $request->status;
                    $settings->update();

            }else{
                $settings = new ClientSettings();
                $settings->client_id = $clientID;
                $settings->shop_id = $shop_id;
                $settings->key = 'waiter_call_status';
                $settings->value = $request->status;
                $settings->save();
            }
            return response()->json([
                'success' => 1,

            ]);


        } catch (\Throwable $th) {
              return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }

    public function onOffPlaySound(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try {
            $query = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','waiter_call_on_off_sound')->first();
            $setting_id = isset($query->id) ? $query->id : '';

            if(!empty($setting_id) || $setting_id != '')
            {
                $settings = ClientSettings::find($setting_id);
                    $settings->value = $request->status;
                    $settings->update();

            }else{
                $settings = new ClientSettings();
                $settings->client_id = $clientID;
                $settings->shop_id = $shop_id;
                $settings->key = 'waiter_call_on_off_sound';
                $settings->value = $request->status;
                $settings->save();
            }
            return response()->json([
                'success' => 1,

            ]);


        } catch (\Throwable $th) {
              return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }

    public function selectPlaySound(Request $request)
    {
        try {

            $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
            $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
            $query = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','waiter_call_sound')->first();
            $setting_id = isset($query->id) ? $query->id : '';

            if(!empty($setting_id) || $setting_id != '')
            {
                $settings = ClientSettings::find($setting_id);
                $settings->value = $request->sound;
                $settings->update();

            }else{
                $settings = new ClientSettings();
                $settings->client_id = $clientID;
                $settings->shop_id = $shop_id;
                $settings->key = 'waiter_call_sound';
                $settings->value = $request->sound;
                $settings->save();
            }

            return response()->json([
                'success' => 1,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }


    public function acceptCallWaiter(Request $request)
    {
        try {
            $id = $request->id;
            $call_waiter = CallWaiter::where('id',$id)->first();

            if($call_waiter){
                $call_waiter->read = 1;
                $call_waiter->save();
             }

             return response()->json([
                'success' => 1,

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
