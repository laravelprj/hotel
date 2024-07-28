<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookArea;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Room;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\RoomBookedDate;
use App\Models\Booking;
use App\Models\BookingRoomList;
use App\Models\RoomNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookConfirm;


class AdminBookingController extends Controller
{
    public function BookingList(){
        $allData = Booking::orderBy('id','desc')->get();
    return view('backend.booking.booking_list',compact('allData'));
    }

    public function EditBooking($id){
        $editData = Booking::with('room')->find($id);
        return view('backend.booking.edit_booking',compact('editData'));
    }
    public function UpdateStatusBooking(Request $request,$id){
        $booking = Booking::find($id);
        $booking->payment_status = $request->payment_status;
        $booking->status = $request->status;
        $booking->save();

        //Send mail

        $sendmail = Booking::find($id);
        $data = [
            'check_in' => $sendmail->check_in,
            'check_out' => $sendmail->check_out,
            'name' => $sendmail->name,
            'email' => $sendmail->email,
            'phone' => $sendmail->phone,
        ];

        Mail::to($sendmail->email)->send(new BookConfirm($data));

        /// End Sent Email 


        $notification = array(
            'message' => 'Information Updated Successfully',
            'alert-type' => 'success'
        ); 
       return redirect()->back()->with($notification);

    }

    public function UpdateDateBooking(Request $request, $id){
        if ($request->available_room < $request->number_of_rooms) {

            $notification = array(
                'message' => 'Something Want To Wrong!',
                'alert-type' => 'error'
            ); 
            return redirect()->back()->with($notification);  
        }

        $booking = Booking::find($id);
        $booking->number_of_rooms = $request->number_of_rooms;
        $booking->check_in = date('Y-m-d',strtotime($request->check_in));
        $booking->check_out = date('Y-m-d',strtotime($request->check_out));
        $booking->save();

        RoomBookedDate::where('booking_id',$id)->delete();

        $sdate = date('Y-m-d',strtotime($request->check_in));
        $edate = date('Y-m-d',strtotime($request->check_out));
        $eldate =Carbon::create($edate)->subDay();
        $d_period = CarbonPeriod::create($sdate,$eldate);

        foreach($d_period as $period){
            $booked = new RoomBookedDate();
            $booked->booking_id = $id;
            $booked->room_id = $booking->rooms_id;
            $booked->book_date = date('Y-m-d',strtotime($period));
            $booked->save();
        }

        $notification = array(
            'message' => 'Booking Updated Successfully',
            'alert-type' => 'success'
        ); 
        return redirect()->back()->with($notification);   

    }

    public function BookingRoomStore($booking_id,$room_number_id){
        $booking = Booking::find($booking_id);
        $check_data = BookingRoomList::where('booking_id',$booking_id)->count();

        if ($check_data < $booking->number_of_rooms) {
            $assign_room = new BookingRoomList();
            $assign_room->booking_id = $booking_id;
            $assign_room->room_id = $booking->rooms_id;
            $assign_room->room_number_id = $room_number_id ;
            $assign_room->save();

            $notification = array(
             'message' => 'Room Assign Successfully',
             'alert-type' => 'success'
         ); 
         return redirect()->back()->with($notification);   
 
         }else {
 
             $notification = array(
                 'message' => 'Room Already Assign',
                 'alert-type' => 'error'
             ); 
             return redirect()->back()->with($notification);   
        }
    }

    public function DeleteAssign($id){
        $assign_room = BookingRoomList::find($id);
        $assign_room->delete();
        $notification = array(
            'message' => 'Assign Room Deleted Successfully',
            'alert-type' => 'success'
        ); 
        return redirect()->back()->with($notification); 
    }

    //AJAX
//     - tìm kiếm hóa đơn đang chỉnh sửa
// - tìm tất cả ngày mà hóa đơn đó đặt
// - tìm các hóa đơn có ngày đặt cùng với hóa đơn tại cùng 1 phòng ->bk_id
// - lấy các id của hóa đơn có bk-id của bước truocc (bây giờ sẽ có id của các hóa đơn có ngày đặt và phòng trùng với hóa đơn hiện tại)
// - bookingroomlist:: lấy các id số phòng theo id của các hóad đơn trùng
// - : Tìm tất cả các số phòng thuộc cùng một loại phòng với đặt phòng hiện tại, không thuộc vào danh sách các phòng đã được gán cho các booking, và có trạng thái 'Active'.

// lấy các số phòng chưa được ặt
    public function AssignRoom($booking_id){
        $booking = Booking::find($booking_id);
        $booking_date_array = RoomBookedDate::where('booking_id',$booking_id)->pluck('book_date')->toArray();
        $check_date_booking_ids = RoomBookedDate::whereIn('book_date',$booking_date_array)->distinct()->pluck('booking_id')->toArray();
        $booking_ids = Booking::whereIn('id',$check_date_booking_ids)->pluck('id')->toArray();
        $assign_room_ids = BookingRoomList::whereIn('booking_id',$booking_ids)->pluck('room_number_id')->toArray();
        $room_numbers = RoomNumber::where('rooms_id',$booking->rooms_id)->whereNotIn('id',$assign_room_ids)->where('status','Active')->get();

        return view('backend.booking.assign_room',compact('booking','room_numbers'));

    }

    
    public function DownloadInvoice($id){
        $editData = Booking::with('room')->find($id);
        $pdf = Pdf::loadView('backend.booking.booking_invoice',compact('editData'))->setPaper('a4')
        ->setOption([
            'tempDir' => public_path(),
            'chroot' => public_path()
        ]);
        return $pdf->download('invoice.pdf');

    }



}
