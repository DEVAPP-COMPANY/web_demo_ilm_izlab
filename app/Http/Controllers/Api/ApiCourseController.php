<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Course;
use App\Models\Backend\CourseTeacher;
use App\Models\Backend\Teacher;
use Carbon\Carbon;
use App\Models\Backend\AppUser;
use Illuminate\Support\Facades\Validator;

class ApiCourseController extends Controller
{
    public function addCenterCourse(Request $request, $center_id){
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
                'science_id' => 'required',
                'monthly_payment' => 'required',
            ],
            [
                'name.required' => 'Input Name',
                'science_id.required' => 'Select Science',
                'monthly_payment.required' => 'Input Monthly Payment',
            ]);
            
            if($validator->fails()){
                return $this->sendResponse(null, false, $validator->errors());
            }

            Course::insert([
                'name' => $request->name,
                'center_id' => $center_id,
                'science_id' => $request->science_id,
                'monthly_payment' => $request->monthly_payment,
                'created_at' => Carbon::now()
            ]);
    
            return $this->sendResponse(null, true, "Insert Course");
    
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
        
    }

    public function deleteCourse($course_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $course = Course::find($course_id);
        if ($course != null) {
            $course->delete();
            return $this->sendResponse(null, true, "Delete This Course");
        }else{
            return $this->sendResponse(null, true, "Not Found Course");
        }
    }
}
