<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;



class UserController extends Controller
{
   public function index(){
  return view('frontend.index');
}

  public function dashboard(){
    return view('frontend.dashboard.user_dashboard');
  }
  public function UserProfile(){

    $id = Auth::user()->id;
    $profileData = User::find($id);
    return view('frontend.dashboard.edit_profile',compact('profileData'));

}
public function ProfileStore(Request $request){
  $id= Auth::user()->id;
  $data = User::find($id);
  $data->name= $request->name;
  $data->email= $request->email;
  $data->phone= $request->phone;
  $data->address= $request->address;
  if($request->file('photo')){
      $file= $request->file('photo');
      @unlink(public_path('upload/user_images/'.$data->photo));
      $filename= date('YmdHi').$file->getClientOriginalName();
      $file->move(public_path('upload/user_images'),$filename);
      $data['photo']= $filename;
  }
  $data->save();

  $notify = array(
      'message' => ' Profile Update Succesfully',
      'alert-type' => 'success',
  );
  return redirect()->back()->with($notify);

}

public function UserChangePassword(){

    $id= Auth::user()->id;
    $profileData = User::find($id);
   return view('frontend.dashboard.user_changePassword');
}

public function PasswordUpdate(Request $request){

  $request->validate([
      'old_password' => 'required',
      'new_password' => 'required|confirmed'
  ]);

  if(!Hash::check($request->old_password, auth::user()->password)){

      $notification = array(
          'message' => 'Old Password Does not Match!',
          'alert-type' => 'error'
      );

      return back()->with($notification);

  }

  /// Update The New Password 
  User::whereId(auth::user()->id)->update([
      'password' => Hash::make($request->new_password)
  ]);

  $notification = array(
      'message' => 'Password Change Successfully',
      'alert-type' => 'success'
  );

  return back()->with($notification); 

}// End Method 

public function logout(Request $request)
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();
    
    $notify = array(
      'message' => ' Logout Succesfully',
      'alert-type' => 'success',
  );
    return redirect('/login')->with($notify);
}

public function UserBooking(){
  $id = Auth::user()->id;
  $allData = Booking::where('user_id',$id)->orderBy('id','desc')->get();
  return view('frontend.dashboard.user_booking',compact('allData'));
}

public function UserInvoice($id){
  $editData = Booking::with('room')->find($id);
  $pdf = Pdf::loadView('backend.booking.booking_invoice',compact('editData'))->setPaper('a4')->setOption([
      'tempDir' => public_path(),
      'chroot' => public_path(),
  ]);
  return $pdf->download('invoice.pdf');
}

}
