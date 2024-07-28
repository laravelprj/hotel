<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Contact;
use Illuminate\Support\Carbon;
class GalleryController extends Controller
{
    public function AllGallery(){

        $gallery = Gallery::latest()->get();
        return view('backend.gallery.all_gallery',compact('gallery'));

    }

    public function AddGallery(){
        return view('backend.gallery.add_gallery');
    }

    public function StoreGallery(Request $request){

        $images = $request->file('photo_name');

        foreach ($images as $img) {
        $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        $img->move(public_path('upload/gallery/'),$name_gen);
        $save_url = 'upload/gallery/'.$name_gen;

        Gallery::insert([
            'photo_name' => $save_url,
            'created_at' => Carbon::now(), 
        ]);
        } //  end foreach 

        $notification = array(
            'message' => 'Gallery Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery')->with($notification);

    }

    public function EditGallery($id){

        $gallery = Gallery::find($id);
        return view('backend.gallery.edit_gallery',compact('gallery'));

    }// End Method 

    public function UpdateGallery(Request $request){

        $gal_id = $request->id;
        $img = $request->file('photo_name');
        $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        $img->move(public_path('upload/gallery/'),$name_gen);
        $save_url = 'upload/gallery/'.$name_gen;
        Gallery::find($gal_id)->update([
            'photo_name' => $save_url, 
        ]); 

        $notification = array(
            'message' => 'Gallery Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery')->with($notification);  

    }// End Method 

    public function DeleteGallery($id){


        $item = Gallery::findOrFail($id);
        $img = $item->photo_name;
        unlink($img);

        Gallery::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Gallery Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


     }
     public function DeleteGalleryMultiple(Request $request){
        $images = $request->input('selectedItem');

        foreach ($images as $imgId){
            $link = Gallery::find($imgId)->value('photo_name');
            unlink($link);
            Gallery::find($imgId)->delete();
        }
        $notification = array(
            'message' => 'Selected Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
}

public function ShowGallery(){
    $gallery = Gallery::latest()->get();
    return view('frontend.gallery.show_gallery',compact('gallery'));
}

 public function ContactUs(){
    return view('frontend.contact.contact_us');
 }
 public function StoreContactUs(Request $request){

    Contact::insert([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'subject' => $request->subject,
        'message' => $request->message,
        'created_at' => Carbon::now(),
    ]);

    $notification = array(
        'message' => 'Your Message Send Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification); 

 }
 public function AdminContactMessage(){

    $contact = Contact::latest()->get();
    return view('backend.contact.contact_message',compact('contact'));

 }
}
