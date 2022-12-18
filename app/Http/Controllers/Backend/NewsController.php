<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\TrainingCenter;
use App\Models\Backend\Subscription;
use App\Models\Backend\News;
use App\Models\Backend\AppUser;

class NewsController extends Controller
{
    public function addNews($id){
        return view('backend.training_center.news.center_news_add', compact('id'));
    }

    public function store(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'content' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'image.required' => 'Upload Image',
            'content.required' => 'Input Content',
        ]);

        $image = $request->file('image');

        $image_name = Str::random(20);
        $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'upload/news/image/';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path.$image_full_name;
        $success = $image->move($upload_path,$image_full_name);

        News::insert([
            'center_id' => $id,
            'title' => $request->title,
            'image' => $save_url_image,
            'content' => $request->content,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Center Post Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('training_center.detail', $id)->with($notification);
    }

    public function edit($id){
        $news = News::findOrFail($id);
        return view('backend.training_center.news.center_news_edit', compact('news'));
    }

    public function update(Request $request, $id){
        $news = News::findOrFail($id);
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'content.required' => 'Input Content',
        ]);

        $old_image = $request->old_image;

        if ($request->file('image')) {
            unlink($old_image);
            $image = $request->file('image');

            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/news/image/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $image->move($upload_path,$image_full_name);

            News::findOrFail($id)->update([
                'title' => $request->title,
                'image' => $save_url_image,
                'content' => $request->content,
            ]);
    
            $notification = array(
                'message' => 'News Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('training_center.detail', $news->center_id)->with($notification);
        }

        News::findOrFail($id)->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $notification = array(
            'message' => 'News Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('training_center.detail', $news->center_id)->with($notification);

        
    }

    public function newsAccept($id){
        $news = News::findOrFail($id);
        $center_id = $news->center_id;
        define('API_ACCESS_KEY', 'AAAAY7XSMrU:APA91bE27CbAN8aWlhiqpWZvY-CLvAR5tE8OUVdjQj0tCg6es7TWQegG_Tfg27-wD45ZaTN0N-tZtWjedwtFoLo16UC5Nny_N-QLuEMtDhxefTANH3bmolMLBO-dBRbVSAm-lNdY1Nv3');

        $center = TrainingCenter::findOrFail($center_id);
        $subscribers = Subscription::where('center_id', $center_id)->get();
        $user_ids = array();
        foreach ($subscribers as $subscriber) {
            array_push($user_ids, $subscriber->user_id);
        }
        $users = AppUser::whereIn('id', $user_ids)->get();
        $reg_ids = array();
        foreach ($users as $user) {
            array_push($reg_ids, $user->fcm_token);
        }

        News::findOrFail($id)->update([
            'status' => 'accept',
        ]);

        if ($reg_ids != []) {
            $data = array(
                "registration_ids" => $reg_ids,
                "notification" => array( 
                    "title" => $center->name,
                    "body" => $news->title
                ));
                $data_string = json_encode($data);
                // echo "The Json Data : ".$data_string;
                $headers = array ( 'Authorization: key=' . API_ACCESS_KEY, 'Content-Type: application/json' );
                $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
                $result = curl_exec($ch);
                curl_close ($ch);
                // echo "<p>&nbsp;</p>";
                // echo "The Result : ".$result;
        }


        $notification = array(
            'message' => 'News Accepted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function newsReject($id){
        News::findOrFail($id)->update([
            'status' => 'reject',
        ]);

        $notification = array(
            'message' => 'News Rejected Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function delete($id){
        $news = News::findOrFail($id);
        $old_image = $news->image;

        unlink($old_image);

        News::findOrFail($id)->delete();

        $notification = array(
            'message' => 'News Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }

    // Waitings

    public function ratingNews(){
        $news = News::where('status', 'waiting')->get();
        return view('backend.waitings.waiting_news_view', compact('news'));
    }
}
