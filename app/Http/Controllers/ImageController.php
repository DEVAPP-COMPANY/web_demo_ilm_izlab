<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backend\Image;
use Illuminate\Support\Str;
use Carbon\Carbon;


class ImageController extends Controller
{
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

        Image::insert([
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
        $image = Image::findOrFail($id);
        $old_img = $image->url;

        unlink($old_img);

        Image::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Image Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
