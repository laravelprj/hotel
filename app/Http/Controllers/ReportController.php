<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class ReportController extends Controller
{
    
    public function BookingReport(Request $request){

        return view('backend.report.booking_report');
    } 


    public function SearchByDate(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $bookings = Booking::where('check_in', '>=', $startDate)->where('check_out', '<=', $endDate)->get();

        return view('backend.report.booking_search_date',compact('startDate','endDate','bookings'));
    }
}
