<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ShopRoom, Staff, ClientSettings};
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShopRoomRequest;



class ShopRoomController extends Controller
{
     public function index()
     {
        $shopRooms = ShopRoom::where('shop_id',Auth::user()->hasOneShop->shop['id'])->get();

        return view('client.rooms.rooms',compact('shopRooms'));
     }

     public function insert()
     {
        $staffs =  Staff::whereIn('type', [1,2])->where('status',1)->where('shop_id',Auth::user()->hasOneShop->shop['id'])->get();
        return view('client.rooms.new_rooms',compact('staffs'));
     }

     public function store(ShopRoomRequest $request)
     {
        try {

                $data = $request->except('_token','staffs');
                $room =  ShopRoom::create($data);
                $room->staffs()->sync($request->staffs);

            return redirect()->route('rooms')->with('success','Rooms has been Inserted SuccessFully....');

        } catch (\Throwable $th) {
            //throw $th;

            return redirect()->route('rooms')->with('error','Internal Server Error');

        }
     }

     public function changeStatus(Request $request)
     {
             // Service ID & Status

             $roomId = $request->id;
             $status = $request->status;

             try {
                 $room = ShopRoom::find($roomId);
                 $room->status = $status;
                 $room->save();


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
         $shopRoom = ShopRoom::where('id', $id)->first();
         $staffIds = $shopRoom->staffs()->pluck('staffs.id')->toArray();
         $staffs =  Staff::whereIn('type', [1,2])->where('status',1)->where('shop_id',Auth::user()->hasOneShop->shop['id'])->get();
         return view('client.rooms.edit_rooms', compact('shopRoom','staffs','staffIds'));
     }

     public function update(ShopRoomRequest $request)
     {
        try {

            $data = $request->except('_token', 'id', 'staffs');

            // Update the ShopRoom
            $room = ShopRoom::find($request->id);
            $room->update($data);

            // Perform staff synchronization
            $room->staffs()->sync($request->staffs);

            return redirect()->route('rooms')->with('success','Rooms has been Updated SuccessFully....');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('rooms')->with('error','Internal Server Error');

        }
     }

     public function destroy(Request $request)
     {
            $id = $request->id;

         try {
            // Find the ShopRoom instance
            $shopRoom = ShopRoom::findOrFail($id);

            // Detach all related records from the pivot table
            $shopRoom->staffs()->detach();

            // Delete the ShopRoom instance
            $shopRoom->delete();

             return response()->json([
                 'success' => 1,
                 'message' => "Rooms has been Removed SuccessFully..",
             ]);
         } catch (\Throwable $th) {
             //throw $th;
             return response()->json([
                 'success' => 0,
                 'message' => "Internal Server Error!",
             ]);
         }
     }


    public function enableRoom(Request $request)  
    {
        try {
            $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
            $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
            $isChecked = $request->isChecked;
            ClientSettings::updateOrCreate(['key' => 'room_enable_status', 'shop_id' => $shop_id, 'client_id' => $clientID], ['value' => $isChecked]);
            return response()->json([
                'success' => 1,
                'message' => $isChecked == 1 ? 'Rooms has been Enabled.' : 'Rooms has been Disabled.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Oops, Something went wrong!',
            ]);
        }    
    }

}
