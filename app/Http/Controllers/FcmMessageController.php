<?php

namespace App\Http\Controllers;

use App\Models\FcmMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FcmMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = FcmMessage::where('user_id', 0)->get();

        return view('backend.message.message_view', compact('messages'));
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
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'body.required' => 'Input Body',
        ]);

        $data = array(
            "to" => "/topics/news",
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
            'created_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => 'Send FCM Message Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function clearMessage(){
        FcmMessage::truncate();
        $notification = array(
            'message' => 'FCM Messages Cleared Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FcmMessage  $fcmMessage
     * @return \Illuminate\Http\Response
     */
    public function show(FcmMessage $fcmMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FcmMessage  $fcmMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(FcmMessage $fcmMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FcmMessage  $fcmMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FcmMessage $fcmMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FcmMessage  $fcmMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(FcmMessage $fcmMessage)
    {
        //
    }
}
