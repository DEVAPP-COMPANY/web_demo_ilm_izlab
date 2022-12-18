<?php

namespace App\Http\Controllers;

use App\Models\Backend\CenterPost;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\TrainingCenter;
use App\Models\Backend\Subscription;

class CenterPostController extends Controller
{

    public function addNews($id){
        return view('backend.training_center.news.center_news_add', compact('id'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $subscribers = Subscription::where('center_id', $id)->get();
        foreach($subscribers as $item){
            print_r($item->user_id);
            dd($subscribers);
        }

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
        $upload_path = 'upload/post/image/';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path.$image_full_name;
        $success = $image->move($upload_path,$image_full_name);

        CenterPost::insert([
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Backend\CenterPost  $centerPost
     * @return \Illuminate\Http\Response
     */
    public function show(CenterPost $centerPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Backend\CenterPost  $centerPost
     * @return \Illuminate\Http\Response
     */
    public function edit(CenterPost $centerPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Backend\CenterPost  $centerPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CenterPost $centerPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backend\CenterPost  $centerPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(CenterPost $centerPost)
    {
        //
    }
}
