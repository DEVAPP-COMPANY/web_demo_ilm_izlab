<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Review;
use App\Models\Backend\Subscription;

class RatingController extends Controller
{
    public function ratingView(){
        $ratings = Review::all();
        return view('backend.rating.rating_view', compact('ratings'));
    }

    public function ratingDelete($id){
        Review::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Training Center Rating Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }

    public function ratingAccept($id){
        Review::findOrFail($id)->update(['status' => 'accept']);

        $notification = array(
            'message' => 'Training Center Rating Accepted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function ratingWaiting($id){
        Review::findOrFail($id)->update(['status' => 'waiting']);

        $notification = array(
            'message' => 'Training Center Waiting Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function ratingReject($id){
        Review::findOrFail($id)->update(['status' => 'reject']);

        $notification = array(
            'message' => 'Training Center Rejected Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function ratingWaitings(){
        $ratings = Review::where('status', 'waiting')->get();
        return view('backend.waitings.waiting_rating_view', compact('ratings'));
    }

    public function subscriberDelete($id){
        $subscriber = Subscription::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Training Center Subscriber Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
