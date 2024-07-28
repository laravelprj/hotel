<?php

namespace App\Http\Controllers\Backend;

use App\Models\BookArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookareaController extends Controller
{
    public function BookArea()
    {


        $book = BookArea::find(1);
        return view('backend.book_area.book_area', compact('book'));
    }

    public function BookAreaUpdate(Request $request)
    {

        $book_id = $request->id;

        if ($request->file('image')) {

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            // Image::make($image)->resize(1000,1000)->save('upload/bookarea/'.$name_gen);
            $image->move(public_path('upload/book_area'), $name_gen);
            $save_url = 'upload/book_area/' . $name_gen;

            BookArea::findOrFail($book_id)->update([

                'short_title' => $request->short_title,
                'main_title' => $request->main_title,
                'short_desc' => $request->short_desc,
                'link_url' => $request->link_url,
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Book Area Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } else {

            BookArea::findOrFail($book_id)->update([

                'short_title' => $request->short_title,
                'main_title' => $request->main_title,
                'short_desc' => $request->short_desc,
                'link_url' => $request->link_url,
            ]);

            $notification = array(
                'message' => 'Book Area Updated Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } // End Eles 
    }
}
