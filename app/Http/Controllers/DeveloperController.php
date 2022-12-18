<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DeveloperController extends Controller
{
    public function index(){
        $developers = Developer::all();

        return view('backend.developer.developer_view', compact('developers'));
    }

    public function show($id){
        $developer = Developer::findOrFail($id);
        return json_encode([
            'developer' => $developer,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'fullname' => 'required',
            'phone' => 'required',
        ],
        [
            'fullname.required' => 'Input Fullname',
            'phone.required' => 'Input Phone',
        ]);

        $key = Str::random(30);
        Developer::insert([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'api_key' => $key,
            'created_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => 'Developer Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function accept($id){
        
        
        Developer::findOrFail($id)->update([
            'status' => "accept"
        ]);


        $notification = array(
            'message' => 'Developer Accepted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function block($id){
        Developer::findOrFail($id)->update([
            'status' => "block"
        ]);


        $notification = array(
            'message' => 'Developer Blocked Successfully',
            'alert-type' => 'warning'
        );
        return redirect()->back()->with($notification);
    }
}
