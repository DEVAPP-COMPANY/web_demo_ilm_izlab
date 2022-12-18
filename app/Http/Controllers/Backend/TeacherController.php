<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Teacher;
use App\Models\Backend\TrainingCenter;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function teacherAdd($center_id){
        $center = TrainingCenter::findOrFail($center_id);
        return view('backend.training_center.center_teacher.center_teacher_add', compact('center'));
    }

    public function teacherStore(Request $request, $center_id){
        $request->validate([
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
        // dd($request);

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

        $notification = array(
            'message' => 'Teacher Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('training_center.detail', $center_id)->with($notification);
    }

    public function teacherEdit($id){
        $teacher = Teacher::findOrFail($id);
        return view('backend.training_center.center_teacher.center_teacher_edit', compact('teacher'));
    }

    public function teacherUpdate(Request $request, $id){
        $teacher = Teacher::findOrFail($id);
        $center_id = $teacher->center_id;
        $request->validate([
            'name' => 'required',
            'info_link' => 'required',
            'specialization' => 'required',
            'experience' => 'required',
        ],
        [
            'name.required' => 'Input Name',
            'info_link.required' => 'Input Link',
            'specialization.required' => 'Input Specialization',
            'experience.required' => 'Input Experience',
        ]);
        $old_image = $request->old_image;

        if ($request->file('avatar')) {
            unlink($old_image);
            $image = $request->file('avatar');

            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/training_center/teacher/avatar/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $image->move($upload_path,$image_full_name);

            Teacher::findOrFail($id)->update([
                'name' => $request->name,
                'info_link' => $request->info_link,
                'specialization' => $request->specialization,
                'experience' => $request->experience,
                'avatar' => $save_url_image,
            ]);
    
            $notification = array(
                'message' => 'Teacher Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('training_center.detail', $center_id)->with($notification);
        }

        Teacher::findOrFail($id)->update([
            'name' => $request->name,
            'info_link' => $request->info_link,
            'specialization' => $request->specialization,
            'experience' => $request->experience,
        ]);

        $notification = array(
            'message' => 'Teacher Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('training_center.detail', $center_id)->with($notification);
    }

    public function teacherDelete($id){
        $teacher = Teacher::findOrFail($id);
        $old_image = $teacher->avatar;

        unlink($old_image);
        Teacher::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Teacher Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
