<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\TeamStoreRequest;
use App\Models\Team;
use Intervention\Image\Image;
class TeamController extends Controller
{
    public function AllTeam(){
        $team= Team::latest()->get();
        return view('backend.team.all_team',compact('team'));
    }
    public function AddTeam(){
        return view('backend.team.add_team');
    }
    public function EditTeam($id){

        $team = Team::findOrFail($id);
        return view('backend.team.edit_team',compact('team'));
    
    }
    public function StoreTeam(Request $request, TeamStoreRequest $request2){
        if($request->file('image') != null){}
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        // Image::resize(550,670)->save('upload/team/'.$name_gen);
        $image->move(public_path('upload/team'),$name_gen);
        $save_url = 'upload/team/'.$name_gen;

        $check = Team::insert([
            'name' => $request->name,
            'postion' => $request->postion,
            'facebook' => $request->facebook,
            'image' => $save_url,
        ]);
        if($check){
            $notification = array(
                'message' => 'Team Data Inserted Successfully',
                'alert-type' => 'success'
            );
        }else{
            $notification = array(
                'message' => 'Please try again',
                'alert-type' => 'error'
            );
        }
       

        return redirect()->route('all.team')->with($notification);

    }

    
    public function UpdateTeam(Request $request){

        $team_id = $request->id;

        if($request->file('image') != null){
            $getLink = Team::where('id',$team_id)->pluck('image')->first();
            unlink($getLink);
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            // Image::make($image)->resize(550,670)->save('upload/team/'.$name_gen);
            $image->move(public_path('upload/team'),$name_gen);
            $save_url = 'upload/team/'.$name_gen;
            Team::findOrFail($team_id)->update([
                'name' => $request->name,
                'postion' => $request->postion,
                'facebook' => $request->facebook,
                'image' => $save_url,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Team Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.team')->with($notification);


        } else {

            Team::findOrFail($team_id)->update([

                'name' => $request->name,
                'postion' => $request->postion,
                'facebook' => $request->facebook, 
                // 'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Team Updated Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.team')->with($notification);

        } 

}
    public function DeleteTeam(string $id){
        $team = Team::findOrFail($id);
        if($team->image != null){
            unlink($team->image);
            Team::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Team Image Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }else{
            Team::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Team Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }

    }


}