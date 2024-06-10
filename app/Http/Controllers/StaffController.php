<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Staff,RoomStaff, TableStaff, Order};
use App\Http\Requests\StaffRequest;
use Illuminate\Support\Facades\Auth;


class StaffController extends Controller
{
    //

    public function index()
    {
        $staffs = Staff::where('shop_id',Auth::user()->hasOneShop->shop['id'])->get();
        return view('client.staffs.staffs',compact('staffs'));
    }

    public function insert()
    {

        return view('client.staffs.new_staffs');
    }

    public function store(StaffRequest $request)
    {
        try {
            $data = $request->except('_token');
            $staff = Staff::create($data);

            return redirect()->route('staffs')->with('success','Staff has been Inserted SuccessFully....');
        } catch (\Throwable $th) {
            return redirect()->route('staffs')->with('error','Internal Server Error');
        }

    }

    public function edit($id)
    {
        $staff = Staff::where('id',$id)->first();
        return view('client.staffs.edit_staffs',compact('staff'));
    }

    public function update(StaffRequest $request)
    {
        try {
            $data = $request->except('_token','id');
            $staff = Staff::find($request->id);
            $staff->update($data);

            return redirect()->route('staffs')->with('success','Staff has been Updated SuccessFully....');
        } catch (\Throwable $th) {
            return redirect()->route('staffs')->with('error','Internal Server Error');

        }
    }

    public function changeStatus(Request $request)
    {
        // Client ID & Status
        $client_id = $request->id;
        $status = $request->status;

        try
        {
            $client = Staff::find($client_id);
            $client->status = $status;
            $client->update();

            return response()->json([
                'success' => 1,
            ]);

        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
            ]);
        }
    }


    public function destroy(Request $request)
    {
        $staff_id = $request->id ?? null;

        try {
            $room_staff = RoomStaff::where('staffs_id', $staff_id)->count();
            $table_staff = TableStaff::where('staffs_id', $staff_id)->count();
            $orders_staff = Order::where('staff_id', $staff_id)->count();

            if($room_staff > 0){
                return response()->json([
                    'success' => 0,
                    'message' => "You cannot remove staff as long as staff are assigned to Rooms!",
                ]);
            }

            if($table_staff > 0){
                return response()->json([
                    'success' => 0,
                    'message' => "You cannot remove staff as long as staff are assigned to Tables!",
                ]);
            }

            if($orders_staff > 0){
                return response()->json([
                    'success' => 0,
                    'message' => "You cannot remove staff as long as staff are assigned to Orders!",
                ]);
            }

            Staff::where('id', $staff_id)->delete();
            return response()->json([
                'success' => 1,
                'message' => "Staff has been Removed.",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }
}

