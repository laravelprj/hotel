<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomBookedDate;
use App\Models\tour_booking;
use App\Models\tour_room_booking;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Notifications\BookingTour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification ;

class BookingTourController extends Controller
{
    public function index()
    {
        $rooms = Room::with('type')->get();
        return view("frontend.booking_tour.booking_tour", compact("rooms"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'persion' => 'required|numeric',
            'number_of_rooms' => 'required|numeric',
            'total_night' => 'required|numeric',
            'total_price' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);

        $data = new tour_booking();
        //  $data->rooms_id = $room->id;
        $data->user_id = Auth::user()->id;
        $data->check_in = date('Y-m-d', strtotime($request['check_in']));
        $data->check_out = date('Y-m-d', strtotime($request['check_out']));
        $data->persion = $request['persion'];
        $data->number_of_rooms = $request->number_of_rooms;
        $data->total_night = $request->total_night;

        $data->total_price = $request->total_price;

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        $data->status = 0;
        $data->created_at = Carbon::now();
        $data->save();

        foreach ($request->info_room as $key => $room_info) {

            if ($room_info != 0) {
                $rooms = new tour_room_booking();
                $rooms->booking_id = $data->id;
                $rooms->room_id = $key;
                $rooms->quantity = $room_info;
                $rooms->created_at = Carbon::now();
                $rooms->save();

                $sdate = date('Y-m-d', strtotime($request['check_in']));
                $edate = date('Y-m-d', strtotime($request['check_out']));
                $eldate = Carbon::create($edate)->subDay();
                $d_period = CarbonPeriod::create($sdate, $eldate);
                foreach ($d_period as $period) {
                    $booked_dates = new RoomBookedDate();
                    $booked_dates->booking_id = $data->id;
                    $booked_dates->room_id = $key;
                    $booked_dates->book_date = date('Y-m-d', strtotime($period));
                    $booked_dates->save();
                }
            }
        }

        $notification = array(
            'message' => 'Booking Tour Added Successfully',
            'alert-type' => 'success'
        ); 
        $user = User::where('role','admin')->get();
    
        // Notification::send($user,new BookingTour($data->id));
    
        return redirect('/')->with($notification); 
    }


    public function CheckRoomAvailabilityTour(Request $request)
    {
        // dd($request->room_ids);
        $av_rooms = [];
        foreach ($request->room_ids as $key => $room_id) {
            $sdate = date('Y-m-d', strtotime($request->check_in));
            $edate = date('Y-m-d', strtotime($request->check_out));
            $alldate = Carbon::create($edate)->subDay();
            $d_period = CarbonPeriod::create($sdate, $alldate);
           
            $dt_array = [];
            foreach ($d_period as $period) {
            
                array_push($dt_array, date('Y-m-d', strtotime($period)));
            }
            $check_date_booking_ids = RoomBookedDate::whereIn('book_date', $dt_array)->distinct()->pluck('booking_id')->toArray();
            // Kết quả là một mảng chứa các booking_id của các phòng đã được đặt trùng với lịch mà khách hàng đã chọn.
            $room = Room::withCount('room_numbers')->find($room_id);
            // withCount('room_numbers') để tính số lượng phòng con (room numbers) của phòng đó.Kết quả là một đối tượng đại diện cho phòng hiện tại (room).
            $bookings = Booking::withCount('assign_rooms')->whereIn('id', $check_date_booking_ids)->where('rooms_id', $room->id)->get()->toArray();
            // nó sẽ đếm những number phòng đã được đặt mà trùng với lịch của phòng mà khách chọn của phòng 4.

            $total_book_room = array_sum(array_column($bookings, 'assign_rooms_count'));
            $av_room = @$room->room_numbers_count - $total_book_room;
            $av_rooms[$room_id] = $av_room;
            $toDate = Carbon::parse($request->check_in);
            $fromDate = Carbon::parse($request->check_out);
            $nights = $toDate->diffInDays($fromDate);
        }
        return response()->json(['available_room' => $av_rooms, 'total_nights' => $nights]);
    }
}
