<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Backend\TrainingCenter;
use App\Models\Backend\Region;
use App\Models\Backend\Science;
use App\Models\Backend\Category;
use App\Models\Backend\Review;
use App\Models\Backend\AppUser;
use App\Models\Backend\Subscription;
use App\Models\Backend\Course;
use App\Models\Backend\CourseTeacher;
use App\Models\Backend\Teacher;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiTrainingCentersController extends Controller
{

    public function getFilterDistance($centers, $lat, $lon){
        $centers = $centers->select(DB::raw('training_centers.id, training_centers.region_id, training_centers.district_id,
                training_centers.name, training_centers.user_name, training_centers.phone,
                training_centers.address, training_centers.comment,  training_centers.monthly_payment_min,
                training_centers.monthly_payment_max, training_centers.main_image, training_centers.latitude,
                training_centers.longitude,
                ( 6371 * acos(
                    cos( radians('.$lat.') )
                    * cos( radians( latitude ) )
                    * cos( radians( longitude )
                    - radians('.$lon.'))
                    + sin( radians('.$lat.') )
                    * sin( radians( latitude ) )
                    )
                ) AS distance'), DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
                COUNT(reviews.rating) as rating_count'))
                ->having('distance', '<', 100)
                ->with('district')
                ->with('region')
                ->with('courses')
                ->with('images');

                $centers = $centers->orderBy('distance', 'asc');
        return $centers;
    }

    public function getFilterCategory($centers, $category_id){
        $category = Category::where('id', $category_id)
        ->with('sciences')->first();
        $sciences = $category->sciences;
        $course_center_ids = array();
        foreach ($sciences as $science) {
            foreach ($science['courses'] as $course) {
                array_push($course_center_ids, $course->center_id);
            }
        }
        $centers = $centers->whereIn('training_centers.id', $course_center_ids)
                ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
                'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
                'training_centers.monthly_payment_max', 'training_centers.main_image',
                'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
                COUNT(reviews.rating) as rating_count'))
                ->with('district')
                ->with('region')
                ->with('courses')
                ->with('images');
        return $centers;
    }

    public function getFilterScience($centers, $science_id){
        $science = Science::where('id', $science_id)->with('courses')->first();
        $courses = $science->courses;
        $course_center_ids = array();
        foreach ($courses as $course) {
            array_push($course_center_ids, $course->center_id);
        }
        $centers = $centers->whereIn('training_centers.id', $course_center_ids)
                ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
                'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
                'training_centers.monthly_payment_max', 'training_centers.main_image',
                'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
                COUNT(reviews.rating) as rating_count'))
                ->with('district')
                ->with('region')
                ->with('courses')
                ->with('images');
        return $centers;
    }

    public function getFilterKeyword($centers, $keyword){
        $centers = $centers->where('training_centers.comment', 'LIKE', "%{$keyword}%")
                ->orWhere('training_centers.name', 'LIKE', "%{$keyword}%")
                ->groupBy('training_centers.id')
                ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
                'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
                'training_centers.monthly_payment_max', 'training_centers.main_image',
                'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
                COUNT(reviews.rating) as rating_count'))
                ->with('district')
                ->with('region')
                ->with('courses')
                ->with('images');
        return $centers;
    }

    public function getFilterRating($centers){
        $centers = $centers->orderBy('rating', 'desc')
                ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
                'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
                'training_centers.monthly_payment_max', 'training_centers.main_image',
                'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
                COUNT(reviews.rating) as rating_count'))
                ->with('district')
                ->with('region')
                ->with('courses')
                ->with('images');
        return $centers;
    }
    public function trainingCetersFilter(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $keyword = $request->keyword;
        $lat = $request->latitude;
        $lon = $request->longitude;
        $user = $this->user();


        if ($request->district_id > 0 ) {


            $centers = TrainingCenter::where('training_centers.status', 'accept')->leftJoin('reviews', 'training_centers.id', '=', 'reviews.center_id')
            ->where('district_id', $request->district_id)
            ->groupBy('training_centers.id')
            ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
            'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
            'training_centers.monthly_payment_max', 'training_centers.main_image',
            'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
            COUNT(reviews.rating) as rating_count'))
            ->with('district')
            ->with('region')
            ->with('courses')
            ->with('images');

            if ($user != null && $request->is_subscribed == true) {
                $user_id = $user->id;
                $subscribers = Subscription::where('user_id', $user_id)->with('center');
                $subscribers = $subscribers->get();

                $center_ids = array();
                foreach ($subscribers as $subscriber) {
                    array_push($center_ids, $subscriber->center->id);
                }
                $centers = $centers->whereIn('training_centers.id', $center_ids);

            }
            if ($request->category_id > 0) {
                $this->getFilterCategory($centers, $request->category_id);
            }
            if ($request->science_id > 0) {
                $this->getFilterScience($centers, $request->science_id);
            }

            if (!empty($keyword)) {
                $this->getFilterKeyword($centers, $keyword);
            }

            if ($request->sort == "distance") {
                 $this->getFilterDistance($centers, $lat, $lon);
            }

            if ($request->sort == "rating") {
                $this->getFilterRating($centers);
            }
            if ($request->limit > 0) {
                $centers = $centers->limit($request->limit)->get();
            }else{
                $centers = $centers->get();
            }

            foreach($centers as $center){
                $images = $center->images;
                unset($center->images);
                $center['images'] = $images->map(function ($item){
                    return $item->image;
                });
                $subscriber_count = count($center->subsriptions);
                $center['subsribers_count'] = $subscriber_count;
                unset($center->subsriptions);
            }
            return $this->sendResponse($centers, true, "District");
        }
        if ($request->region_id > 0 ) {

            $centers = TrainingCenter::where('training_centers.status', 'accept')->leftJoin('reviews', 'training_centers.id', '=', 'reviews.center_id')
            ->where('region_id', $request->region_id)
            ->groupBy('training_centers.id')
            ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
            'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
            'training_centers.monthly_payment_max', 'training_centers.main_image',
            'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
            COUNT(reviews.rating) as rating_count'))
            ->with('district')
            ->with('region')
            ->with('courses')
            ->with('images');

            if ($user != null && $request->is_subscribed == true) {
                $user_id = $user->id;
                $subscribers = Subscription::where('user_id', $user_id)->with('center');
                $subscribers = $subscribers->get();

                $center_ids = array();
                foreach ($subscribers as $subscriber) {
                    array_push($center_ids, $subscriber->center->id);
                }
                $centers = $centers->whereIn('training_centers.id', $center_ids);

            }
            if ($request->category_id > 0) {
                $this->getFilterCategory($centers, $request->category_id);
            }
            if ($request->science_id > 0) {
                $this->getFilterScience($centers, $request->science_id);
            }

            if (!empty($keyword)) {
                $this->getFilterKeyword($centers, $keyword);
            }

            if ($request->sort == "distance") {
                 $this->getFilterDistance($centers, $lat, $lon);
            }

            if ($request->sort == "rating") {
                $this->getFilterRating($centers);
            }
            if ($request->limit > 0) {
                $centers = $centers->limit($request->limit)->get();
            }else{
                $centers = $centers->get();
            }


            foreach($centers as $center){
                $images = $center->images;
                unset($center->images);
                $center['images'] = $images->map(function ($item){
                    return $item->image;
                });
                $subscriber_count = count($center->subsriptions);
                $center['subsribers_count'] = $subscriber_count;
                unset($center->subsriptions);
            }
            return $this->sendResponse($centers, true, "Region");
        }else {
            $centers = TrainingCenter::where('training_centers.status', 'accept')->leftJoin('reviews', 'training_centers.id', '=', 'reviews.center_id')
            ->groupBy('training_centers.id')
            ->select('training_centers.id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
            'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
            'training_centers.monthly_payment_max', 'training_centers.main_image',
            'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
            COUNT(reviews.rating) as rating_count'))
            ->with('district')
            ->with('region')
            ->with('courses')
            ->with('images');

            if ($user != null && $request->is_subscribed == true) {
                $user_id = $user->id;
                $subscribers = Subscription::where('user_id', $user_id)->with('center');
                $subscribers = $subscribers->get();

                $center_ids = array();
                foreach ($subscribers as $subscriber) {
                    array_push($center_ids, $subscriber->center->id);
                }
                $centers = $centers->whereIn('training_centers.id', $center_ids);
            }
            if ($request->category_id > 0) {
                $this->getFilterCategory($centers, $request->category_id);
            }
            if ($request->science_id > 0) {
                $this->getFilterScience($centers, $request->science_id);
            }

            if (!empty($keyword)) {
                $this->getFilterKeyword($centers, $keyword);
            }

            if ($request->sort == "distance") {
                 $this->getFilterDistance($centers, $lat, $lon);
            }

            if ($request->sort == "rating") {
                $this->getFilterRating($centers);
            }
            if ($request->limit > 0) {
                $centers = $centers->limit($request->limit)->get();
            }else{
                $centers = $centers->get();
            }


            foreach($centers as $center){
                $images = $center->images;
                unset($center->images);
                $center['images'] = $images->map(function ($item){
                    return $item->image;
                });
                $subscriber_count = count($center->subsriptions);
                $center['subsribers_count'] = $subscriber_count;
                unset($center->subsriptions);
            }
            return $this->sendResponse($centers, true, "All");
        }
    }

    public function makeRating(Request $request){
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
            $rating = Review::where('user_id', $user_id)->where('center_id', $request->center_id)->first();
            if ($rating) {
                $rating = Review::where('id', $rating->id)->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);
                return $this->sendResponse(null, true, "Update Rating");
            }
            Review::insert([
                'user_id' => $user_id,
                'center_id' => $request->center_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => "waiting",
                'created_at' => Carbon::now()
            ]);

            return $this->sendResponse(null, true, "Insert Rating");
        }else {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function setSubscriber(Request $request){
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
            $subscriber = Subscription::where('user_id', $user_id)->where('center_id', $request->center_id)->first();
            if ($subscriber) {
                $subscriber->delete();
                return $this->sendResponse(null, true, "Delete Subscriber");
            }
            Subscription::insert([
                'user_id' => $user_id,
                'center_id' => $request->center_id,
                'created_at' => Carbon::now()
            ]);

            return $this->sendResponse(null, true, "Insert Subscriber");
        }else {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }



    public function getMyCenters(){
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
            $with_user = TrainingCenter::where('user_id', $user_id)->get();

            $subscribers = Subscription::where('user_id', $user_id)->with('center');
            $subscribers = $subscribers->get();

            $center_ids = array();
            foreach ($subscribers as $subscriber) {
                array_push($center_ids, $subscriber->center->id);
            }
            $centers = TrainingCenter::whereIn('training_centers.id', $center_ids)
            ->join('reviews', 'training_centers.id', '=', 'reviews.center_id')
            ->groupBy('training_centers.id')
            ->select('training_centers.id', 'training_centers.user_id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
            'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
            'training_centers.monthly_payment_max', 'training_centers.main_image',
            'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
            COUNT(reviews.rating) as rating_count'))
            ->with('district')
            ->with('region')
            ->with('courses')
            ->with('images');

            $centers = $centers->get();
            foreach($centers as $center){
                $images = $center->images;
                unset($center->images);
                $center['images'] = $images->map(function ($item){
                    return $item->image;
                });
                $subscriber_count = count($center->subsriptions);
                $center['subsribers_count'] = $subscriber_count;
                unset($center->subsriptions);
            }

            return $this->sendResponse($centers, true, "");
        }else {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function getRating($center_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
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
        return $this->sendResponse($ratings, true, "");
    }

    public function courseTeachers($id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $courses = CourseTeacher::where('course_id', $id)
        ->with('teachers')
        ->get();
        $teacher_ids = array();
        foreach ($courses as $course) {
            array_push($teacher_ids, $course->teacher_id);
        }
        $teachers = Teacher::whereIn('id', $teacher_ids)->get();
        return $this->sendResponse($teachers, true, "");
    }

    public function getCourses($center_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $courses = Course::where('center_id', $center_id)->with('science')->get();
        return $this->sendResponse($courses, true, "");
    }

    public function checkSubscriber($center_id){
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
            $check = false;
            $subscriber = Subscription::where('user_id', $user_id)->where('center_id', $center_id)->first();
            if ($subscriber) {
                $check = true;
            }

            return $this->sendResponse($check, true, "");
        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function getMyOwnCenters(){
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

            $centers = TrainingCenter::where('training_centers.user_id', $user_id)
            ->leftJoin('reviews', 'training_centers.id', '=', 'reviews.center_id')
            ->groupBy('training_centers.id')
            ->select('training_centers.id', 'training_centers.user_id', 'training_centers.region_id', 'training_centers.district_id', 'training_centers.name',
            'training_centers.phone', 'training_centers.address', 'training_centers.comment', 'training_centers.monthly_payment_min',
            'training_centers.monthly_payment_max', 'training_centers.main_image',
            'training_centers.latitude', 'training_centers.longitude',DB::raw('COALESCE(AVG(reviews.rating), 0) as rating,
            COUNT(reviews.rating) as rating_count'))
            ->with('district')
            ->with('region')
            ->with('courses')
            ->with('images');

            $centers = $centers->get();
            foreach($centers as $center){
                $images = $center->images;
                unset($center->images);
                $center['images'] = $images->map(function ($item){
                    return $item->image;
                });
                $subscriber_count = count($center->subsriptions);
                $center['subsribers_count'] = $subscriber_count;
                unset($center->subsriptions);
            }

            return $this->sendResponse($centers, true, "");

        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function subscriberCenterAll($center_id){
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
            $subscribers = Subscription::where('center_id', $center_id)->get();
            foreach($subscribers as $subscriber){
                $date = Carbon::parse($subscriber->created_at)->format('H:m d/m/Y');
                $subscriber['date'] = $date;
                $subscriber['user_fullname'] = $subscriber->user->fullname;
                $subscriber['user_avatar'] = $subscriber->user->avatar;
                unset($subscriber->user);
            }
            return $this->sendResponse($subscribers, true, "");

        }else{
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }
}
