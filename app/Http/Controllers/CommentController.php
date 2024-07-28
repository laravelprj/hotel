<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Carbon;

class CommentController extends Controller
{
    public function CommentStore(Request $request){

        Comment::insert([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'message' => $request->message,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Comment Added Successfully Admin will approved',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }

    public function AllComment(){
        $allcomment = Comment::latest()->get();
        return view('backend.comment.all_comment',compact('allcomment'));
    }

    public function UpdateCommentStatus(Request $request){
        $commentId = $request->input('comment_id');
        $checked = $request->input('is_checked');
        
        Comment::where('id',$commentId)->update([
            'status' => $checked
        ]);

        return response()->json(['message' => 'Comment Status Updated Successfully']);
   }
}
