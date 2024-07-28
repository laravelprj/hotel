<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Room;

use Carbon\Carbon;

class RoomTypeController extends Controller
{
    public function RoomTypeList(){
        $allData = RoomType::orderBy('id','desc')->get();
        return view('backend.allroom.room_type',compact( 'allData'));
    }

    public function RoomTypeAdd(){
        return view('backend.allroom.add_room_type');
    }
    public function RoomTypeStore(Request $request){
       $idRommType = RoomType::insertGetId([
            'name' => $request->name,
            'created_at' => Carbon::now(),
        ]);
        Room::insert([
            'roomtype_id' => $idRommType,
        ]);
        $notify = array(
            'message' => 'Add Roomtype Succesfully',
            'alert-type' => 'success',
        );
        return redirect()->route('room.type.list')->with($notify);
    }
   


//     public function RoomTypeDelete(string $id){
//         $team = RoomType::findOrFail($id);
//         if($team->image != null){
//             unlink($team->image);
//             RoomType::findOrFail($id)->delete();
//             $notification = array(
//                 'message' => 'Team Image Deleted Successfully',
//                 'alert-type' => 'success'
//             );
//             return redirect()->back()->with($notification);
//         }else{
//             RoomType::findOrFail($id)->delete();
//             $notification = array(
//                 'message' => 'Team Deleted Successfully',
//                 'alert-type' => 'success'
//             );
//             return redirect()->back()->with($notification);
//         }
// }
}