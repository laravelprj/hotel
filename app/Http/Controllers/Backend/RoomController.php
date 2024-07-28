<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Multi_image;
use App\Models\RoomNumber;
use App\Models\RoomType;

class RoomController extends Controller
{
public function EditRoom($id){
        $editData = Room::find($id);
        $multiimgs = Multi_image::where('rooms_id',$id)->get();
        $basic_facility= Facility::where('rooms_id','id')->get();
        $allroomNo = RoomNumber::where('rooms_id',$id)->get();
        return view('backend.allroom.rooms.edit_rooms',compact('editData','basic_facility','multiimgs','allroomNo'));
}
    
public function UpdateRoom(Request $request, $id){
        // dd($request->all());
        $room = Room::find($id);
        $room->roomtype_id = $room->roomtype_id;
        $room->total_adult = $request->total_adult;
        $room->total_child = $request->total_child;
        $room->room_capacity = $request->room_capacity;
        $room->price = $request->price;

        $room->size = $request->size;
        $room->view = $request->view;
        $room->bed_style = $request->bed_style;
        $room->discount = $request->discount;
        $room->short_desc = $request->short_desc;
        $room->description = $request->description; 
        /// Update Single Image 
       
        if($request->file('image')){
        $image = $request->file('image');

        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/rooming'),$name_gen);
        $save_url = 'upload/rooming/'.$name_gen;
        $room->image =  $save_url;
        }


        $room->save();
      

        if($request->facility_name == NULL){

            $notification = array(
                'message' => 'Sorry! Not Any Basic Facility Select',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);

        } else{
            Facility::where('rooms_id',$room->id)->delete();
            $fcount = count($request->facility_name);
           for($i=0;$i<$fcount;$i++){
                $facility = new Facility();
                $facility->rooms_id= $room->id;
                $facility->facility_name = $request->facility_name[$i];
                $facility->save();
           }
     }


     if($room->save()){
        $files = $request->multi_img;
        if(isset($files) && is_array($files)){
            $subimage = Multi_image::where('rooms_id',$id)->get();
            // foreach ($subimage as $item) {
            //     @unlink(public_path('upload/rooming/multi_img/' . $item->multi_img));
            // }

            Multi_image::where('rooms_id',$room->id)->delete();

            foreach($files as $file){
                $imgName = date('YmdHi').$file->getClientOriginalName();
                $data= Multi_image::where('rooms_id',$room->id);
                $file->move('upload/rooming/multi_img/.',$imgName);
                $subimage['multi_img'] = $imgName;
                $multiImg = new Multi_image();
                $multiImg->rooms_id=$room->id;
                $multiImg->multi_img = $imgName;
                $multiImg->save();
            }
        }

        }
        $notification = array(
            'message' => 'Room Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

}
public function StoreRoomNumber(Request $request,$id){

    $data = new RoomNumber();
    $data->rooms_id = $id;
    $data->room_type_id = $request->room_type_id;
    $data->room_no = $request->room_no;
    $data->status = $request->status;
    $data->save();

    $notification = array(
        'message' => 'Room Number Added Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification); 

}
public function EditRoomNumber($id){

    $editroomno = RoomNumber::find($id);
    return view('backend.allroom.rooms.edit_rooms_no',compact('editroomno'));

}
public function UpdateRoomNumber(Request $request, $id){

    $data = RoomNumber::find($id);
    $data->room_no = $request->room_no;
    $data->status = $request->status;
    $data->save();

   $notification = array(
        'message' => 'Room Number Updated Successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('room.type.list')->with($notification); 

}//End Method 


public function DeleteRoomNumber($id){

    RoomNumber::find($id)->delete();

    $notification = array(
        'message' => 'Room Number Deleted Successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('room.type.list')->with($notification); 

}//End Method

 public function MultiImageDelete($id){
        $deletedata = Multi_image::where('id',$id)->first();
        if($deletedata){
   
            $imagePath = $deletedata->multi_img;
      
            // Check if the file exists before unlinking 
            if ( $imagePath) {
               
                @unlink(public_path('upload/rooming/multi_img/' .$imagePath));
               echo "Image Unlinked Successfully";
            }else{
                echo "Image does not exist";
            }

            //  Delete the record form database 

            Multi_image::where('id',$id)->delete();

        }

        $notification = array(
            'message' => 'Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
}

public function DeleteRoom($id){
    $room = Room::find($id);
    if (file_exists($room->image) AND !empty($room->image)) {
        @unlink($room->image);
     }

     $subimage = Multi_image::where('rooms_id',$room->id)->get()->toArray();
   
     if (isset($subimage) && $subimage != null ) {
 
         foreach ($subimage as $value) {
            if (!empty($value)) {
            @unlink('upload/rooming/multi_img/'.$value['multi_img']);
            }
         }
     }

     RoomType::where('id',$room->roomtype_id)->delete();
     Multi_image::where('rooms_id',$room->id)->delete();
     Facility::where('rooms_id',$room->id)->delete();
     RoomNumber::where('rooms_id',$room->id)->delete();
     $room->delete();

     $notification = array(
         'message' => 'Room Deleted Successfully',
         'alert-type' => 'success'
     );

     return redirect()->back()->with($notification);  


}

}
