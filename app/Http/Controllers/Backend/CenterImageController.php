<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backend\CenterImage;
use Illuminate\Support\Str;
use Carbon\Carbon;


class CenterImageController extends Controller
{

    public function viewImage(){
        $images = CenterImage::where('category_id')->get();
        return view('backend.category.science.science_view', compact('images'));
    }

    public function imageStore(Request $request){
        $request->validate([
            'url' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ],
        [
            'url.required' => 'Please Upload Image',
        ]);

        $url = $request->file('url');

        $image_name = Str::random(5);
        $ext = strtolower($url->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'upload/publicimg/';    //Creating Sub directory in Public folder to put image
        $save_url = $upload_path.$image_full_name;
        $success = $url->move($upload_path,$image_full_name);

        CenterImage::insert([
            'url' => $save_url,
            'created_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => 'Image Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function imageDelete($id){
        $image = CenterImage::findOrFail($id);
        $old_img = $image->url;

        unlink($old_img);

        CenterImage::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Image Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
