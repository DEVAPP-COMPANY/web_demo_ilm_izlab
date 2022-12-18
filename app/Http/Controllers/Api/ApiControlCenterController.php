<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Backend\TrainingCenter;
use App\Models\Backend\AppUser;
use App\Models\Backend\CenterImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Backend\Teacher;
use App\Models\Backend\CourseTeacher;

class ApiControlCenterController extends Controller
{
    
    public function addCenterRequest(Request $request){
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
            $user_id = $user->id;
            $main_image = $request->file('main_image');

            $validator = Validator::make(request()->all(), [
                'region_id' => 'required',
                'district_id' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'comment' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'monthly_payment_min' => 'required',
                'monthly_payment_max' => 'required',
                'main_image' => 'required|mimes:jpg,png,jpeg',
                'latitude' => 'required',
                'longitude' => 'required',
            ],
            [
                'region_id.required' => 'Select Region',
                'district_id.required' => 'Select District',
                'name.required' => 'Input Name',
                'user_name.required' => 'Input User Name',
                'phone.required' => 'Input Phone',
                'address.required' => 'Input Address',
                'comment.required' => 'Input Comment',
                'monthly_payment_min.required' => 'Input Monthly Payment Min',
                'monthly_payment_max.required' => 'Input Monthly Payment Max',
                'main_image.required' => 'Upload Main Image',
                'main_image.mimes' => 'Upload Image File',
                'latitude.required' => 'Input Latitude',
                'longitude.required' => 'Input Longitude',
            ]);

            if($validator->fails()){
                return $this->sendResponse(null, false, $validator->errors());
            }


            $image_name = Str::random(20);
            $ext = strtolower($main_image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/training_center/main_image/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $main_image->move($upload_path,$image_full_name);

            TrainingCenter::insert([
                'user_id' => $user_id,
                'region_id' => $request->region_id,
                'district_id' => $request->district_id,
                'name' => $request->name,
                'user_name' => $request->user_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'comment' => $request->comment,
                'monthly_payment_min' => $request->monthly_payment_min,
                'monthly_payment_max' => $request->monthly_payment_max,
                'main_image' => $save_url_image,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => "waiting",
                'created_at' => Carbon::now()
            ]);
            
            return $this->sendResponse(null, true, "Insert Center");
        }else {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function addCenterImage(Request $request, $center_id){
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
                'image' => 'required|mimes:jpeg,png,jpg,gif,svg,bmp',
            ],
            [
                'image.required' => 'Upload Image',
                'image.mimes' => 'Upload Type Image',
            ]);
    
            if($validator->fails()){
                return $this->sendResponse(null, false, $validator->errors());
            }
    
            $image = $request->file('image');
    
            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/training_center/image/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $image->move($upload_path,$image_full_name);
    
            CenterImage::insert([
                'center_id' => $center_id,
                'image' => $save_url_image,
                'created_at' => Carbon::now()
            ]);
    
            return $this->sendResponse(null, true, "Insert Center Image");
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

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

    public function getCenterTeachers($center_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $teachers = Teacher::where('center_id', $center_id)->get();
        // $news = $news->get();
        // foreach($news as $new){
        //     $date = Carbon::parse($new->created_at)->format('H:m d/m/Y');
        //     $new['date'] = $date;
        //     $new['content'] = "";
        //     $new['center_image'] = $new->centers->main_image;
        //     $new['center_name'] = $new->centers->name;
        //     $new['district_name'] = $new->centers->district->district_name;
        //     unset($new->centers);
        // }
        return $this->sendResponse($teachers, true, "");
    }

    public function getTeachers(){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $teachers = Teacher::get();
        // foreach($news as $new){
        //     $date = Carbon::parse($new->created_at)->format('H:m d/m/Y');
        //     $new['date'] = $date;
        //     $new['content'] = "";
        //     $new['center_image'] = $new->centers->main_image;
        //     $new['center_name'] = $new->centers->name;
        //     $new['district_name'] = $new->centers->district->district_name;
        //     unset($new->centers);
        // }
        return $this->sendResponse($teachers, true, "");
    }

    public function addCenterTeacher(Request $request, $center_id){
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
                'name' => 'required',
                'info_link' => 'required',
                'specialization' => 'required',
                'experience' => 'required',
                'avatar' => 'required',
            ],
            [
                'name.required' => 'Input Name',
                'info_link.required' => 'Input Link',
                'specialization.required' => 'Input Specialization',
                'experience.required' => 'Input Experience',
                'avatar.required' => 'Upload Avatar',
            ]);
            
            if($validator->fails()){
                return $this->sendResponse(null, false, $validator->errors());
            }
    
            $image = $request->file('avatar');

            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/training_center/teacher/avatar/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $image->move($upload_path,$image_full_name);

            Teacher::insert([
                'center_id' => $center_id,
                'name' => $request->name,
                'info_link' => $request->info_link,
                'specialization' => $request->specialization,
                'experience' => $request->experience,
                'avatar' => $save_url_image,
                'created_at' => Carbon::now()
            ]);
    
            return $this->sendResponse(null, true, "Insert Teacher");
    
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        } 
    }

    public function connectCourseTeacher(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $course_id = $request->course_id;
        $teacher_id = $request->teacher_id;
        $user = AppUser::where('token', $this->getToken())->first();

        if ($user) {
        $exists = CourseTeacher::where('course_id', $course_id)->where('teacher_id', $teacher_id)->first();

            if (!$exists) {
                CourseTeacher::insert([
                    'course_id' => $request->course_id,
                    'teacher_id' => $request->teacher_id,
                ]);
                return $this->sendResponse(null, true, "Connect");
            }else{
                
                $exists->delete();
                return $this->sendResponse(null, true, "Disconnect");
            }
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        } 
    }

    public function deleteTeacher($teacher_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $teacher = Teacher::find($teacher_id);
        if ($teacher != null) {
            $old_image = $teacher->avatar;
            unlink($old_image);
            $teacher->delete();
            return $this->sendResponse(null, true, "Delete This teacher");
        }else{
            return $this->sendResponse(null, true, "Not Found teacher");
        }
    }

    public function deleteCenterImage(Request $request){
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
        
        if ($user == null) {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }else {
            $image = CenterImage::where('image', $request->image)->first();
            if ($image != null) {
                $center = TrainingCenter::where('id', $image->center_id)->where('user_id', $user->id)->first();
                if ($center != null) {
                    $old_image = $image->image;
                    unlink($old_image);
                    $image->delete();
                    return $this->sendResponse(null, true, "Delete This Image");
                }else{
                    return $this->sendResponse(null, false, "Not Found Center");
                }
            }else{
                return $this->sendResponse(null, false, "Not Found Image");
            }
        }
    }

    public function updateMainImage(Request $request, $center_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $center = TrainingCenter::where('id', $center_id)->first();
        $main_image = $request->file('main_image');

        $validator = Validator::make(request()->all(), [
            'main_image' => 'required|mimes:jpg,png,jpeg',
        ],
        [
            'main_image.required' => 'Upload Main Image',
        ]);

        if($validator->fails()){
            return $this->sendResponse(null, false, $validator->errors());
        }

        if ($center != null) {
            $old_image = $center->main_image;
            unlink($old_image);

            $image_name = Str::random(20);
            $ext = strtolower($main_image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/training_center/main_image/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $main_image->move($upload_path,$image_full_name);

            TrainingCenter::findOrFail($center_id)->update([
                'main_image' => $save_url_image,
            ]);

            return $this->sendResponse(null, true, "Update This Main Image");
        }else{
            return $this->sendResponse(null, false, "Not Found Center");
        }
    }
}
