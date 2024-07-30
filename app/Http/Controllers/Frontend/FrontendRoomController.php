<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Multi_image;
use App\Models\Facility;
use App\Models\RoomBookedDate;
use App\Models\Booking;

use Carbon\Carbon;
use Carbon\CarbonPeriod;



class FrontendRoomController extends Controller
{
    public function AllRoomFrontend(){
        $rooms = Room::latest()->get();
       return view('frontend.room.all_rooms',compact('rooms'));
    }

    public function RoomDetail($id){
        $room_id = $id;
        $detail = Room::withCount('room_numbers')->find($id);
        $imgMul = Multi_image::where('rooms_id',$id)->get();
        $facility = Facility::where('rooms_id',$id)->get();
        // dd($facility );
        $otherRooms = Room::where('id','!=',$id)->orderBy('id','desc')->limit(2)->get();
        return view('frontend.room.detail_room',compact('detail','imgMul','facility','otherRooms','room_id'));
    }

    public function BookingSearch(Request $request){
        $request->flash();
        if ($request->check_in == $request->check_out) {
            $notification = array(
                'message' => 'Something want to worng',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        
        $sdate = date('Y-m-d',strtotime($request->check_in));
        $edate = date('Y-m-d',strtotime($request->check_out));
        $all_date = Carbon::create($edate)->subDay();
        $d_period = CarbonPeriod::create( $sdate,  $all_date);
        $dt_array= [];

        foreach( $d_period as $period){
            array_push($dt_array , date('Y-m-d',strtotime($period)));
        } 

        $check_date_booking_ids = RoomBookedDate::whereIn('book_date', $dt_array)->distinct()->pluck('booking_id')->toArray();
        $rooms = Room::withCount('room_numbers')->where('status',1)->get();
        return view('frontend.room.search_room',compact('check_date_booking_ids','rooms'));
         
    }
    public function SearchRoomDetails(Request $request,$id){
  
        $request->flash();
        $roomdetails = Room::find($id);
        $multiImage = Multi_image::where('rooms_id',$id)->get();
        $facility = Facility::where('rooms_id',$id)->get();
        
        $otherRooms = Room::where('id','!=', $id)->orderBy('id','DESC')->limit(2)->get();
        $room_id = $id;
        return view('frontend.room.detail_search_room',compact('roomdetails','multiImage','facility','otherRooms','room_id'));

    }

    //AJAX
    public function CheckRoomAvailability(Request $request){
        $sdate = date('Y-m-d',strtotime($request->check_in));
        $edate = date('Y-m-d',strtotime($request->check_out));
        $alldate = Carbon::create($edate)->subDay();
        $d_period = CarbonPeriod::create($sdate,$alldate);
        $dt_array = [];
        foreach ($d_period as $period) {
           array_push($dt_array, date('Y-m-d', strtotime($period)));
        }
        $check_date_booking_ids = RoomBookedDate::whereIn('book_date',$dt_array)->distinct()->pluck('booking_id')->toArray();
        // Kết quả là một mảng chứa các booking_id của các phòng đã được đặt trùng với lịch mà khách hàng đã chọn.
        $room = Room::withCount('room_numbers')->find($request->room_id);
        // withCount('room_numbers') để tính số lượng phòng con (room numbers) của phòng đó.Kết quả là một đối tượng đại diện cho phòng hiện tại (room).
        $bookings = Booking::withCount('assign_rooms')->whereIn('id',$check_date_booking_ids)->where('rooms_id',$room->id)->get()->toArray();
        // nó sẽ đếm những number phòng đã được đặt mà trùng với lịch của phòng mà khách chọn của phòng 4.

        $total_book_room = array_sum(array_column($bookings,'assign_rooms_count'));
        $av_room = @$room->room_numbers_count-$total_book_room;
        
        $toDate = Carbon::parse($request->check_in);
        $fromDate = Carbon::parse($request->check_out);
        $nights = $toDate->diffInDays($fromDate);
        return response()->json(['available_room'=>$av_room, 'total_nights'=>$nights ]);
    }
}
