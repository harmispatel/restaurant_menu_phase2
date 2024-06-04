<?php

namespace App\Http\Controllers;

use App\Models\{ShopTable, Staff, ClientSettings};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShopTableRequest;

class ShopTableController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $shopTables = ShopTable::where('shop_id',Auth::user()->hasOneShop->shop['id'])->get();
        return view('client.tables.tables',compact('shopTables'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $staffs =  Staff::where('type',1)->where('status',1)->get();
        return view('client.tables.new_tables',compact('staffs'));
    }

    // Store a newly created resource in storage.
    public function store(ShopTableRequest $request)
    {
        try {
            $input = $request->except('_token','staffs');
             $table = ShopTable::create($input);
             $table->staffs()->sync($request->staffs);

            return redirect()->route('tables')->with('success','Table has been Created.');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('tables')->with('error','Internal Server Error');
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $shopTable = ShopTable::where('id', $id)->first();
        $staffIds = $shopTable->staffs()->pluck('staffs.id')->toArray();
        $staffs =  Staff::where('type',1)->where('status',1)->get();

        return view('client.tables.edit_table', compact('shopTable','staffs','staffIds'));
    }

    // Update the specified resource in storage.
    public function update(ShopTableRequest $request)
    {
        try {
            $input = $request->except('_token', 'id', 'shop_id','staffs');
            $table = ShopTable::find($request->id);
            $table->update($input);

            $table->staffs()->sync($request->staffs);

            return redirect()->route('tables')->with('success','Table has been Updated.');
        } catch (\Throwable $th) {
            return redirect()->route('tables')->with('error','Internal Server Error!');
        }
    }

    // Remove the specified resource from storage.
    public function destroy(Request $request)
    {
        $id = $request->id;
        try {
            $shopTable = ShopTable::findOrFail($id);

            $shopTable->staffs->detach();

            $shopTable->delete();
        //    ShopTable::where('id',$request->id)->delete();
            return response()->json([
                'success' => 1,
                'message' => "Table has been Removed.",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }

    // Change Status of Specified Table
    public function status(Request $request)
    {
        $tableId = $request->id;
        try {
            $table = ShopTable::find($tableId);
            $table->status = ($table->status == 1) ? 0 : 1;
            $table->save();
            return response()->json([
                'success' => 1,
                'message' => 'Status has been Changed.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function enableTable(Request $request)  
    {
        try {
            $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
            $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
            $isChecked = $request->isChecked;
            ClientSettings::updateOrCreate(['key' => 'table_enable_status', 'shop_id' => $shop_id, 'client_id' => $clientID], ['value' => $isChecked]);
            return response()->json([
                'success' => 1,
                'message' => $isChecked == 1 ? 'Tables has been Enabled.' : 'Tables has been Disabled.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Oops, Something went wrong!',
            ]);
        }    
    }
}
