<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
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
        $id = $request->id;

        try {
            Staff::where('id',$id)->delete();

            return response()->json([
                'success' => 1,
                'message' => "Staff has been Removed SuccessFully..",
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

