<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Course;
use App\Models\Backend\CourseTeacher;
use App\Models\Backend\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courseStore(Request $request, $center_id){
        $request->validate([
            'name' => 'required',
            'science_id' => 'required',
            'monthly_payment' => 'required',
        ],
        [
            'name.required' => 'Input Name',
            'science_id.required' => 'Select Science',
            'monthly_payment.required' => 'Input Monthly Payment',
        ]);

        Course::insert([
            'name' => $request->name,
            'center_id' => $center_id,
            'science_id' => $request->science_id,
            'monthly_payment' => $request->monthly_payment,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Center Course Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function courseEdit(Request $request, $id){
        $course = Course::findOrFail($id);
        $teachers = Teacher::query()
        ->where('center_id', $request->center_id)
        ->with(['checked' => function ($hasMany) use ($id) {
            $hasMany->where('course_id', $id);
        }])->get();
        return json_encode([
            'course' => $course,
            'teachers' => $teachers,
        ]);
    }

    public function courseUpdate(Request $request, $id){

        Course::findOrFail($id)->update([
            'name' => $request->name,
            'science_id' => $request->science_id,
            'monthly_payment' => $request->monthly_payment,
        ]);
    
        // $notification = array(
        //     'message' => 'Training Center Updated Successfully',
        //     'alert-type' => 'success'
        // );
        return response()->json(['success' => 'Successfully']);
    }


    public function courseDelete($id){
        Course::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Center Course Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }

    public function connect(Request $request){
        $course_id = $request->course_id;
        $teacher_id = $request->teacher_id;

        $exists = CourseTeacher::where('course_id', $course_id)->where('teacher_id', $teacher_id)->first();

        if (!$exists) {
            CourseTeacher::insert([
                'course_id' => $request->course_id,
                'teacher_id' => $request->teacher_id,
            ]);
            return response()->json(['success' => 'Connect Successfully']);        
        }else{
            
            $exists->delete();
            return response()->json(['success' => 'Disconnect Successfully']);
        }
    }
}
