<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Backend\Category;
use App\Models\Backend\AppUser;


class ApiCategoryController extends Controller
{

    public function addCategory(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $user = AppUser::where('token', $this->getToken())->first();

        if ($user) {
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'icon' => 'required',
            ],
            [
                'title.required' => 'Input Title',
                'icon.required' => 'Upload Icon',
            ]);
            
            if($validator->fails()){
                return $this->sendResponse(null, false, $validator->errors());
            }
    
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
    
            return $this->sendResponse(null, true, "Insert Category");
    
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
        
    }

    public function categories(){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $categories = Category::with('sciences')->get();
        return $this->sendResponse($categories, true, "");
    }
}
