<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\BookArea;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Room;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\RoomBookedDate;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Dd;
use Stripe;
use App\Notifications\CompleteBooking;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{

    public function Checkout()
    {

        if (Session::has('book_date')) {
            $book_data = Session::get('book_date');
            $room = Room::find($book_data['room_id']);
            $toDate = Carbon::parse($book_data['check_in']);
            $fromDate = Carbon::parse($book_data['check_out']);
            $nights = $toDate->diffInDays($fromDate);
            // dd($nights);
            return view('frontend.checkout.checkout', compact('book_data', 'room', 'nights'));
        } else {
            $notification = array(
                'message' => 'Something want to wrong!',
                'alert-type' => 'error'
            );
            return redirect('/')->with($notification);
        }
    }

    public function UserBookingStore(Request $request)
    {

        if ($request->available_room < $request->number_of_rooms) {

            $notification = array(
                'message' => 'Something want to wrong!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        Session::forget('book_date');

        $data = array();
        $data['number_of_rooms'] = $request->number_of_rooms;
        $data['available_room'] = $request->available_room;
        $data['persion'] = $request->persion;
        $data['check_in'] = date('Y-m-d', strtotime($request->check_in));
        $data['check_out'] = date('Y-m-d', strtotime($request->check_out));
        $data['room_id'] = $request->room_id;



        Session::put('book_date', $data);
        return redirect()->route('checkout');
    }

    public function CheckoutStore(Request $request)
    {

        $user = User::where('role', 'admin')->get();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);

        $book_data = Session::get('book_date');
        $toDate = Carbon::parse($book_data['check_in']);
        $fromDate = Carbon::parse($book_data['check_out']);
        $total_nights = $toDate->diffInDays($fromDate);

        $room = Room::find($book_data['room_id']);
        $subtotal = $room->price * $total_nights * $book_data['number_of_rooms'];
        $discount = ($room->discount / 100) * $subtotal;
        $total_price = $subtotal - $discount;
        $code = rand(000000000, 999999999);

        if ($request->payment_method == 'Stripe') {
            // dd(__('STRIPE_SECRET'));
            Stripe\Stripe::setApiKey('sk_test_51PD2LVRvVc28HE9aRV5K48dnQxpvuNnKKefA0y3i5AlVfsnJmC5b86fJVHsQtHq1ZA4k4R3RFwoifv0hNNXS30xN00vop5RMGd');
            $s_pay = Stripe\Charge::create([
                "amount" => $total_price * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment For Booking. Booking No " . $code,
            ]);

            if ($s_pay['status'] == 'succeeded') {
                $payment_status = 1;
                $transation_id = $s_pay->id;
            } else {
                $notification = array(
                    'message' => 'Sorry Payment Field',
                    'alert-type' => 'error'
                );
                return redirect('/')->with($notification);
            }
        } else if ($request->payment_method == 'Vnpay') {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "http://hotel-booking.test/?message=success";
            $vnp_TmnCode = "G08VYFJ7";  
            $vnp_HashSecret = "S23CIO1FLSGYQKN1T59KFT194KHAZI5H"; // Chuỗi bí mật
            $vnp_TxnRef = $code; // Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này 
            $vnp_OrderInfo = 'Thanh toan hoa don';
            $vnp_OrderType = 'bill payment';
            $vnp_Amount = $total_price * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
            // Add Params of 2.0.1 Version
            // $vnp_ExpireDate = $_POST['txtexpire'];
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                // "vnp_ExpireDate" => $vnp_ExpireDate,
            );
        
            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }
        
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
        
            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
        
            $returnData = array(
                'code' => '00', 'message' => 'success', 'data' => $vnp_Url
            );
        
            if ($returnData['message'] == 'success') {
                $payment_status = 1;
                $transation_id = '';
            }
        
            $redirectUrl = null;
            if ($request->payment_method == 'Vnpay') {
                $redirectUrl = $vnp_Url;
            } else {
                echo json_encode($returnData);
            }
        
            // Các đoạn mã phía sau
            // ...
        
            // Chuyển hướng nếu cần thiết
            if ($redirectUrl) {
                  echo "<script>
                    alert('Bạn sẽ được chuyển hướng đến trang thanh toán.')
                window.location.href='$redirectUrl';</script>";
                exit();
            }
            // end vnnpay
        }
        
         else {
            $payment_status = 0;
            $transation_id = '';
        }

        $data = new Booking();

        $data->rooms_id = $room->id;
        $data->user_id = Auth::user()->id;
        $data->check_in = date('Y-m-d', strtotime($book_data['check_in']));
        $data->check_out = date('Y-m-d', strtotime($book_data['check_out']));
        $data->persion = $book_data['persion'];
        $data->number_of_rooms = $book_data['number_of_rooms'];
        $data->total_night = $total_nights;

        $data->actual_price = $room->price;
        $data->subtotal = $subtotal;
        $data->discount = $discount;
        $data->total_price = $total_price;
        $data->payment_method = $request->payment_method;
        $data->transation_id =  $transation_id;
        $data->payment_status = $payment_status;

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip_code = $request->zip_code;
        $data->address = $request->address;

        $data->code = $code;
        $data->status = 0;
        $data->created_at = Carbon::now();
        $data->save();

        $sdate = date('Y-m-d', strtotime($book_data['check_in']));
        $edate = date('Y-m-d', strtotime($book_data['check_out']));
        $eldate = Carbon::create($edate)->subDay();
        $d_period = CarbonPeriod::create($sdate, $eldate);
        foreach ($d_period as $period) {
            $booked_dates = new RoomBookedDate();
            $booked_dates->booking_id = $data->id;
            $booked_dates->room_id = $room->id;
            $booked_dates->book_date = date('Y-m-d', strtotime($period));
            $booked_dates->save();
        }
        Session::forget('book_date');
        $notification = array(
            'message' => 'Booking Added Successfully',
            'alert-type' => 'success'
        );

        Notification::send($user, new CompleteBooking($data->id));

        return redirect('/')->with($notification);
    }

    public function MarkAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();


        if ($notification) {
            $notification->MarkAsRead();
        }

        return response()->json(['count' => $user->unreadNotifications()->count()]);
    }
}
