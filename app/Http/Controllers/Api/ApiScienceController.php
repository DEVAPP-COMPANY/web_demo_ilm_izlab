<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Category;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\AppUser;
use App\Models\Backend\Science;
use Illuminate\Support\Facades\Validator;

class ApiScienceController extends Controller
{
    public function addScience(Request $request, $category_id){
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
            $upload_path = 'upload/science/icon/';    //Creating Sub directory in Public folder to put icon
            $save_url_image = $upload_path.$image_full_name;
            $success = $icon->move($upload_path,$image_full_name);

            Science::insert([
                'category_id' => $category_id,
                'title' => $request->title,
                'icon' => $save_url_image,
                'created_at' => Carbon::now()
            ]);
    
            return $this->sendResponse(null, true, "Insert Science");
    
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
        
    }

    public function getScience(){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $sciences = Science::get();
        return $this->sendResponse($sciences, true, "");
    }
}
