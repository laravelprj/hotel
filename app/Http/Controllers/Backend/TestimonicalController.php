<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Carbon\Carbon;


class TestimonicalController extends Controller
{
    public function AllTestimonical(){
        $testimonial = Testimonial::latest()->get();
        return view('backend.testimonial.all_testimonial',compact('testimonial'));
    }
    public function AddTestimonical(){
        return view('backend.testimonial.add_testimonial');
    }
    public function StoreTestimonical(Request $request){
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move(public_path('upload/testimonial'),$name_gen);
        $save_url = 'upload/testimonial/'.$name_gen;

        Testimonial::insert([
            'name' => $request->name,
            'city' => $request->city,
            'message' => $request->message,
            'image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Testimonial Data Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.testimonial')->with($notification);
}

    public function EditTestimonical($id){
        $testimonial = Testimonial::find($id);
        return view('backend.testimonial.edit_testimonial',compact('testimonial'));
    }

    public function UpdateTestimonial(Request $request){

        $test_id = $request->id;
    
        if($request->file('image')){
            $unlink = Testimonial::find($test_id)->image;
            unlink($unlink);

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('upload/testimonial'),$name_gen);
            $save_url = 'upload/testimonial/'.$name_gen;

            Testimonial::findOrFail($test_id)->update([

            'name' => $request->name,
            'city' => $request->city,
            'message' => $request->message,
            'image' => $save_url,
            'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Testimonial Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.testimonial')->with($notification);


        } else {

            Testimonial::findOrFail($test_id)->update([

                'name' => $request->name,
                'city' => $request->city,
                'message' => $request->message, 
                'created_at' => Carbon::now(),
                ]);

                $notification = array(
                    'message' => 'Testimonial Updated Without Image Successfully',
                    'alert-type' => 'success'
                );

                return redirect()->route('all.testimonial')->with($notification);

        } // End Eles  

    }// End Method 
    public function DeleteTestimonial($id){

        $item = Testimonial::findOrFail($id);
        $img = $item->image;
        unlink($img);

        Testimonial::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Testimonial Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


     } 
}