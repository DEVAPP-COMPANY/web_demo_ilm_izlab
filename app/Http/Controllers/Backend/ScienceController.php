<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\Science;

class ScienceController extends Controller
{
    public function scienceView($category_id){
        $sciences = Science::where('category_id', $category_id)->get();
        $category = Category::findOrFail($category_id);
        return view('backend.category.science.science_view', compact('sciences', 'category'));
    }

    public function scienceStore(Request $request, $category_id){
        $request->validate([
            'title' => 'required',
            'icon' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'icon.required' => 'Upload Icon',
        ]);
        // dd($request);
        $icon = $request->file('icon');

        $image_name = Str::random(20);
        $ext = strtolower($icon->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'upload/science/icon/';    //Creating Sub directory in Public folder to put icon
        $save_url_image = $upload_path.$image_full_name;
        $success = $icon->move($upload_path,$image_full_name);

        Science::insert([
            'category_id' => $category_id,
            'title' => $request->title,
            'icon' => $save_url_image,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Category Science Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function scienceEdit($id){
        $science = Science::findOrFail($id);
        return view('backend.category.science.science_edit', compact('science'));
    }

    public function scienceUpdate(Request $request, $id){
        $science = Science::findOrFail($id);
        $category_id = $science->category_id;
        $request->validate([
            'title' => 'required',
        ],
        [
            'title.required' => 'Input Title',
        ]);

        $old_icon = $request->old_icon;

        if ($request->file('icon')) {
            unlink($old_icon);
            $icon = $request->file('icon');

            $image_name = Str::random(20);
            $ext = strtolower($icon->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/science/icon/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $icon->move($upload_path,$image_full_name);

            Science::findOrFail($id)->update([
                'title' => $request->title,
                'icon' => $save_url_image,
            ]);
    
            $notification = array(
                'message' => 'Category Science Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.science', $category_id)->with($notification);
            
        }
        Science::findOrFail($id)->update([
            'title' => $request->title,
        ]);

        $notification = array(
            'message' => 'Category Science Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.science', $category_id)->with($notification);
    }

    public function scienceDelete($id){
        $science = Science::findOrFail($id);
        $old_icon = $science->icon;

        unlink($old_icon);

        Science::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Science Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
