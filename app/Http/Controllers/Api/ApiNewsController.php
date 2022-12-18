<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Backend\News;

class ApiNewsController extends Controller
{
    public function addNews(Request $request){
        
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $validator = Validator::make(request()->all(), [
            'center_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg'
        ],
        [
            'center_id.required' => 'Select Center',
            'title.required' => 'Input Title',
            'content.required' => 'Input Content',
            'image.required' => 'Upload Main Image',
            'image.mimes' => 'Upload Image File'
        ]);

        if($validator->fails()){
            return $this->sendResponse(null, false, $validator->errors());
        }

        $image = $request->file('image');

        $image_name = Str::random(20);
        $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'upload/news/image/';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path.$image_full_name;
        $success = $image->move($upload_path,$image_full_name);

        News::insert([
            'center_id' => $request->center_id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $save_url_image,
            'created_at' => Carbon::now()
        ]);
        
        return $this->sendResponse(null, true, "Insert News");
    }

    public function getNewsAll(){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $news = News::where('status', 'accept')->orWhere('status', 'waiting')->orderBy('id', 'desc')->limit(500)->get();
        foreach($news as $new){
            $date = Carbon::parse($new->created_at)->format('H:m d/m/Y');
            $new['date'] = $date;
            $new['content'] = "";
            $new['center_image'] = $new->centers->main_image;
            $new['center_name'] = $new->centers->name;
            $new['district_name'] = $new->centers->district->district_name;
            unset($new->centers);
        }
        return $this->sendResponse($news, true, "");
    }

    public function getNews($center_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $news = News::where('center_id', $center_id)->where('status', 'accept')->with('centers')->orderBy('id', 'desc');
        $news = $news->get();
        foreach($news as $new){
            $date = Carbon::parse($new->created_at)->format('H:m d/m/Y');
            $new['date'] = $date;
            $new['content'] = "";
            $new['center_image'] = $new->centers->main_image;
            $new['center_name'] = $new->centers->name;
            $new['district_name'] = $new->centers->district->district_name;
            unset($new->centers);
        }
        return $this->sendResponse($news, true, "");
    }

    public function getNewsContent($news_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $new = News::where('id', $news_id)->with('centers')->first();
        $date = Carbon::parse($new->created_at)->format('H:m d/m/Y');
        $new['date'] = $date;
        $new['center_image'] = $new->centers->main_image;
        $new['center_name'] = $new->centers->name;
        $new['district_name'] = $new->centers->district->district_name;
        unset($new->centers);

        return $this->sendResponse($new, true, "");
    }

    public function deleteNews($news_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $news = News::find($news_id);
        if ($news != null) {
            $old_image = $news->image;
            unlink($old_image);
            $news->delete();
            return $this->sendResponse(null, true, "Delete This News");
        }else{
            return $this->sendResponse(null, true, "Not Found News");
        }
    }
}
