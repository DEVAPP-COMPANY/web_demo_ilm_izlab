<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\AppUser;
use App\Models\FcmMessage;
use Carbon\Carbon;

class AppUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = AppUser::all();
        return view('backend.app_user.app_user_view', compact('users'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = AppUser::findOrFail($id);
        $messages = FcmMessage::where('user_id', $id)->get();

        return view('backend.app_user.app_user_detail', compact('user', 'messages'));
    }

    public function sendFcm(Request $request, $id){
        $user = AppUser::findOrFail($id);
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'body.required' => 'Input Body',
        ]);
        
        $data = array(
            "to" => $user->fcm_token,
            "notification" => array( 
                "title" => $request->title,
                "body" => $request->body
            ));
            $data_string = json_encode($data);
            // echo "The Json Data : ".$data_string;
            $headers = array ( 'Authorization: key=' . self::API_ACCESS_KEY, 'Content-Type: application/json' );
            $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
            $result = curl_exec($ch);
            curl_close ($ch);
            // echo "<p>&nbsp;</p>";
            // echo "The Result : ".$result;

        FcmMessage::insert([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $id,
            'created_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => 'Send FCM Message Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function status($id, $status){
        AppUser::findOrFail($id)->update([
            'status' => $status,
        ]);

        $notification = array(
            'message' => 'App User Status Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
