<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\CenterImage;
use App\Models\Backend\CourseTeacher;
use App\Models\Backend\Course;
use App\Models\Backend\District;
use App\Models\Backend\Region;
use App\Models\Backend\Science;
use App\Models\Backend\Teacher;
use App\Models\Backend\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\TrainingCenter;
use App\Models\Backend\News;
use App\Models\Backend\Subscription;
use Illuminate\Support\Facades\Hash;
use DB;

class TrainingCenterController extends Controller
{
// detail

    public function centerDetail($center_id){
        $center = TrainingCenter::where('training_centers.id', $center_id)
        ->leftJoin('reviews', 'training_centers.id', '=', 'reviews.center_id')
        ->groupBy('training_centers.id')
        ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name', 
        'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
        'training_centers.monthly_payment_max', 'training_centers.main_image', 
        'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating, 
        COUNT(reviews.rating) as rating_count'))
        ->with('district')
        ->with('region')
        ->with('courses')
        ->with('images')->first();
        $news = News::where('center_id', $center_id)->get();
        $subscribers = Subscription::where('center_id', $center_id)->get();
        $ratings = Review::where('center_id', $center_id)->where('status', '!=', 'reject')
        ->select('id', 'user_id', 'rating', 'comment', 'created_at')
        ->with('user');
        $ratings = $ratings->get();
        foreach($ratings as $rating){
            $date = Carbon::parse($rating->created_at)->format('H:m d/m/Y');
            $rating['date'] = $date;
            $rating['user_fullname'] = $rating->user->fullname;
            $rating['user_avatar'] = $rating->user->avatar;
            unset($rating->user);
        }
        return view('backend.center.center_detail', compact('center','news', 'subscribers', 'ratings'));
    }

    public function newsDetail($news_id){
        $news = News::where('id', $news_id)->first();
        return view('backend.center.news_detail', compact('news'));
    }

// 



    public function trainingCenterView(){
        $centers = TrainingCenter::all();
        return view('backend.training_center.training_center_view', compact('centers'));
    }

    public function trainingCenterAdd(){
        $regions = Region::all();
        return view('backend.training_center.training_center_add', compact('regions'));
    }

    public function getDistrict($region_id){
        $districts = District::where('region_id', $region_id)->get();
        return json_encode($districts);
    }

    public function getCenterImage($center_id){
        $images = CenterImage::where('center_id', $center_id)->get();
        return view('backend.training_center.training_center_view', compact('images'));
    }

    public function trainingCenterStore(Request $request){
        $request->validate([
            'name' => 'required',
            'user_name' => 'required',
            'phone' => 'required',
            'district_id' => 'required',
            'address' => 'required',
            'comment' => 'required',
            'image' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'monthly_payment_min' => 'required',
            'monthly_payment_max' => 'required',
            'parol' => 'required',
        ],
        [
            'name.required' => 'Input Name',
            'user_name.required' => 'Input User Name',
            'phone.required' => 'Input Phone',
            'district_id.required' => 'Select District',
            'address.required' => 'Input Address',
            'comment.required' => 'Input Comment',
            'image.required' => 'Upload Main Image',
            'latitude.required' => 'Input Latitude',
            'longitude.required' => 'Input Longitude',
            'monthly_payment_min.required' => 'Input Monthly Payment Min',
            'monthly_payment_max.required' => 'Input Monthly Payment Max',
            'parol.required' => 'Input Parol',
        ]);
        // dd($request);

        $image = $request->file('image');

        $image_name = Str::random(20);
        $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'upload/training_center/main_image/';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path.$image_full_name;
        $success = $image->move($upload_path,$image_full_name);

        TrainingCenter::insert([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'phone' => $request->phone,
            'region_id' => $request->region_id,
            'district_id' => $request->district_id,
            'address' => $request->address,
            'comment' => $request->comment,
            'main_image' => $save_url_image,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'monthly_payment_min' => $request->monthly_payment_min,
            'monthly_payment_max' => $request->monthly_payment_max,
            'parol' => Hash::make($request->parol),
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Training Center Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.training_center')->with($notification);
    }

    public function trainingCenterEdit($id){
        $regions = Region::all();
        $training_center = TrainingCenter::findOrFail($id);
        return view('backend.training_center.training_center_edit', compact('regions', 'training_center'));
    }

    public function trainingCenterDetail($id){
        $training_center = TrainingCenter::where('training_centers.id', $id)
        ->leftJoin('reviews', 'training_centers.id', '=', 'reviews.center_id')
        ->groupBy('training_centers.id')
        ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name', 
        'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
        'training_centers.monthly_payment_max', 'training_centers.main_image', 
        'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating, 
        COUNT(reviews.rating) as rating_count'))
        ->with('district')
        ->with('region')
        ->with('courses')
        ->with('images')->first();
        $news = News::where('center_id', $id)->get();
        $subscribers = Subscription::where('center_id', $id)->get();
        $ratings = Review::where('center_id', $id)->where('status', '!=', 'reject')
        ->select('id', 'user_id', 'rating', 'comment', 'created_at')
        ->with('user');
        $ratings = $ratings->get();
        foreach($ratings as $rating){
            $date = Carbon::parse($rating->created_at)->format('H:m d/m/Y');
            $rating['date'] = $date;
            $rating['user_fullname'] = $rating->user->fullname;
            $rating['user_avatar'] = $rating->user->avatar;
            unset($rating->user);
        }
        $regions = Region::all();
        $teachers = Teacher::where('center_id', $id)->get();
        $sciences = Science::all();

        return view('backend.training_center.training_center_detail', compact('training_center',
        'ratings', 'news', 'subscribers', 'regions', 'teachers', 'sciences'));
    }

    public function trainingCenterUpdate(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'user_name' => 'required',
            'phone' => 'required',
            'district_id' => 'required',
            'address' => 'required',
            'comment' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'monthly_payment_min' => 'required',
            'monthly_payment_max' => 'required',
        ],
        [
            'name.required' => 'Input Name',
            'user_name.required' => 'Input User Name',
            'phone.required' => 'Input Phone',
            'district_id.required' => 'Select District',
            'address.required' => 'Input Address',
            'comment.required' => 'Input Comment',
            'latitude.required' => 'Input Latitude',
            'longitude.required' => 'Input Longitude',
            'monthly_payment_min.required' => 'Input Monthly Payment Min',
            'monthly_payment_max.required' => 'Input Monthly Payment Max',
        ]);

        // dd($request);

        $old_image = $request->old_image;

        if ($request->file('image')) {
            unlink($old_image);
            $image = $request->file('image');

            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/training_center/main_image/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $image->move($upload_path,$image_full_name);

            TrainingCenter::findOrFail($id)->update([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'phone' => $request->phone,
                'region_id' => $request->region_id,
                'district_id' => $request->district_id,
                'address' => $request->address,
                'comment' => $request->comment,
                'main_image' => $save_url_image,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'monthly_payment_min' => $request->monthly_payment_min,
                'monthly_payment_max' => $request->monthly_payment_max,
            ]);
    
            $notification = array(
                'message' => 'Training Center Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }

        TrainingCenter::findOrFail($id)->update([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'phone' => $request->phone,
            'region_id' => $request->region_id,
            'district_id' => $request->district_id,
            'address' => $request->address,
            'comment' => $request->comment,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'monthly_payment_min' => $request->monthly_payment_min,
            'monthly_payment_max' => $request->monthly_payment_max,
        ]);

        $notification = array(
            'message' => 'Training Center Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function trainingCenterDelete($id){
        $center = TrainingCenter::findOrFail($id);
        $old_image = $center->main_image;

        unlink($old_image);
        TrainingCenter::findOrFail($id)->delete();

        $center_images = CenterImage::where('center_id', $id)->get();
        foreach($center_images as $image){
            unlink($image->image);
            $image->delete();
        }
        $courses = Course::where('center_id', $id)->get();
        foreach($courses as $course){
            $course_teachers = CourseTeacher::where('course_id', $course->id)->get();
            foreach($course_teachers as $course_teacher){
                $course_teacher->delete();
            }
            $course->delete();
        }
        $news = News::where('center_id', $id)->get();
        foreach($news as $new){
            unlink($new->image);
            $new->delete();
        }
        $reviews = Review::where('center_id', $id)->get();
        foreach($reviews as $review){
            $review->delete();
        }
        $subscribers = Subscription::where('center_id', $id)->get();
        foreach($subscribers as $subscriber){
            $subscriber->delete();
        }
        $teachers = Teacher::where('center_id', $id)->get();
        foreach($teachers as $teacher){
            unlink($teacher->avatar);
            $teacher->delete();
        }

        $notification = array(
            'message' => 'Training Center Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->route('all.training_center')->with($notification);
    }


    // Center Image Methods

    public function centerImageView($center_id){
        $center_images = CenterImage::where('center_id', $center_id)->get();
        $center = TrainingCenter::findOrFail($center_id);
        return view('backend.training_center.center.center_image_view', compact('center_images', 'center'));
    }

    public function centerImageStore(Request $request, $center_id){
        $request->validate([
            'image' => 'required',
        ],
        [
            'image.required' => 'Upload Image',
        ]);

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

        $notification = array(
            'message' => 'Center Image Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function centerImageDelete($id){
        $image = CenterImage::findOrFail($id);
        $old_img = $image->image;

        unlink($old_img);

        CenterImage::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Center Image Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }

    public function trainingCenterAccepted($id){
        TrainingCenter::findOrFail($id)->update(['status' => 'accept']);

        $notification = array(
            'message' => 'Training Center Accepted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function trainingCenterWaiting($id){
        TrainingCenter::findOrFail($id)->update(['status' => 'waiting']);

        $notification = array(
            'message' => 'Training Center Waiting Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function trainingCenterRejected($id){
        TrainingCenter::findOrFail($id)->update(['status' => 'reject']);

        $notification = array(
            'message' => 'Training Center Rejected Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function trainingCenterBlocked($id){
        TrainingCenter::findOrFail($id)->update(['status' => 'blocked']);

        $notification = array(
            'message' => 'Training Center Blocked Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function filterCenter(Request $request){
        if ($request->region_id && $request->status) {
            $center = TrainingCenter::where('status', $request->status)->where('region_id', $request->region_id)->get();
        }elseif ($request->region_id) {
            $center = TrainingCenter::where('region_id', $request->region_id)->get();
        }else{
            $center = TrainingCenter::where('status', $request->status)->get();
        }
        return json_encode($center);
    }

    public function trainingCenterWaitings(){
        $centers = TrainingCenter::where('status', 'waiting')->get();
        return view('backend.waitings.waiting_center_view', compact('centers'));
    }
}
