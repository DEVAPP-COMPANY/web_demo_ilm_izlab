<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\Category;

class CategoryController extends Controller
{
    public function categoryView(){
        $categories = Category::all();
        return view('backend.category.category_view', compact('categories'));
    }

    public function categoryAdd(){
        return view('backend.category.category_add');
    }

    public function categoryStore(Request $request){
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
        $upload_path = 'upload/category/icon/';    //Creating Sub directory in Public folder to put icon
        $save_url_image = $upload_path.$image_full_name;
        $success = $icon->move($upload_path,$image_full_name);

        Category::insert([
            'title' => $request->title,
            'icon' => $save_url_image,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function categoryEdit($id){
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    }

    public function categoryUpdate(Request $request, $id){
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
            $upload_path = 'upload/category/icon/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $icon->move($upload_path,$image_full_name);

            Category::findOrFail($id)->update([
                'title' => $request->title,
                'icon' => $save_url_image,
            ]);
    
            $notification = array(
                'message' => 'Category Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);
            
        }
        Category::findOrFail($id)->update([
            'title' => $request->title,
        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);
    }

    public function categoryDelete($id){
        $category = Category::findOrFail($id);
        $old_icon = $category->icon;

        unlink($old_icon);

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
