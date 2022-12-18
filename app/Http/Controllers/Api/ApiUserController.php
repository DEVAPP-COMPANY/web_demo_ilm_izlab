<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DB;
use GuzzleHttp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Backend\AppUser;

class ApiUserController extends Controller
{
    public function getUser(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $user = AppUser::where('token', $this->getToken())->first();
        if ($user) {

            $user_id = $user->id;
            AppUser::findOrFail($user_id)->update([
                'fcm_token' => $request->fcm_token,
            ]);
            $user = AppUser::where('id', $user_id)
            ->select('token', 'fullname', 'avatar', 'phone', 'status')
            ->first();
            return $this->sendResponse($user, true, "");
        }else {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function smsCountIncrement(){
        $sms_count = file_get_contents("../sms_count");
        $sms_count += 1;
        file_put_contents("../sms_count",$sms_count);
    }

    public function updateSmsToken(){
        $sms_token = file_get_contents("../token.sms");

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'notify.eskiz.uz/api/auth/refresh',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$sms_token
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $data = json_decode($response);
            $token = $data->data->token;

        file_put_contents("../token.sms", $token);
        

        return true;
    }
   

    public function checkPhone(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $sms_token = file_get_contents("../token.sms");
        $now_time = Carbon::now();
        $message = 1111;

        $codes = array("33", "71", "88", "90", "91", "94", "93", "95", "99");

        $phone = $request->phone;
        $uz = substr($phone,0,3) == '998';
        $com = substr($phone,3,2);
        $number = substr($phone,3,10);
        $length = strlen($number) == 9;

        if (!empty($phone) && $uz && in_array($com, $codes) && is_numeric($number) && $length) {
            
            $user = AppUser::where('phone', $request->phone)->first();

            if ($user == null) {
                AppUser::insert([
                    'phone' => $request->phone,
                    'status' => 'waiting',
                    'created_at' => Carbon::now()
                ]);
                $user = AppUser::where('phone', $request->phone)->first();
                $user_id = $user->id;
                
                // $client = new GuzzleHttp\Client();
                // $res = $client->request('POST', 'http://notify.eskiz.uz/api/message/sms/send',  [
                //     'headers'  => [
                //         'authorization' => 'Bearer '.$sms_token,
                //     ],
                //     'form_params' => [
                //         'mobile_phone' => $phone,
                //         'message' => "ILM-IZLAB: Tasdiqlash kodi ".$message." DfLZ5h0FzdL",
                //         'from' => 4546,
                //         'callback_url' => 'http://0000.uz/test.php'
                //     ]
                // ]);

                // $this->smsCountIncrement();
    
                AppUser::where('id', $user_id)->update([
                    'sms_code' => $message,
                    'counter' => 0,
                    'sms_time' => $now_time,
                ]);
                $response = [
                    'isReg' => false
                ];
                return $this->sendResponse($response, true, "Not Reg User");
            }elseif ($user->status == 'blocked') {
                return $this->sendResponse($response, false, "User blocked", 0);
            }elseif ($user->status == 'waiting') {
                $user_id = $user->id;
                
                // $client = new GuzzleHttp\Client();
                // $res = $client->request('POST', 'http://notify.eskiz.uz/api/message/sms/send',  [
                //     'headers'  => [
                //         'authorization' => 'Bearer '.$sms_token,
                //     ],
                //     'form_params' => [
                //         'mobile_phone' => $phone,
                //         'message' => "ILM-IZLAB: Tasdiqlash kodi ".$message." DfLZ5h0FzdL",
                //         'from' => 4546,
                //         'callback_url' => 'http://0000.uz/test.php'
                //     ]
                // ]);

                // $this->smsCountIncrement();
    
                AppUser::where('id', $user_id)->update([
                    'sms_code' => $message,
                    'counter' => 0,
                    'sms_time' => $now_time,
                ]);
                $response = [
                    'isReg' => false
                ];
                return $this->sendResponse($response, true, "Not Reg User");
            }else{
                $response = [
                    'isReg' => true
                ];
                return $this->sendResponse($response, true, "");
            }
        }else {

            return $this->sendResponse($phone, false, "Wrong Phone");
        }
    }

    public function registration(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $user = AppUser::where('phone', $request->phone)->where('sms_code', $request->sms_code)->first();
        $message = $request->sms_code;

        if ($user && !empty($message)) {
            if ($user->status == 'blocked') {
                return $this->sendResponse(null, false, "User blocked", 1);
            }
            $user_id = $user->id;
            $sms_time = $user->sms_time;

            
            $now_time = Carbon::now();
            $date1 = new DateTime($sms_time);
            $date2 = new DateTime($now_time);
            $difference = $date1->diff($date2);
            // $diffInSeconds = $difference->s == 0;
            $diffInMinutes = $difference->i  >= 3;

            if ($diffInMinutes) {

                $user = AppUser::where('id', $user_id)->update([
                    'sms_code' => null,
                    'counter' => 0,
                    'sms_time' => null,
                    'token' => null,
                ]);

                return $this->sendResponse(null, false, "Time Out");
            }

            if (strlen($request->password) < 6) {
                return $this->sendResponse(null, false, "Password length min 6 character");
            }else {
                $token = Str::random(30);
        
                $user = AppUser::where('id', $user_id)->update([
                    'fullname' => $request->fullname,
                    'password' => Hash::make($request->password),
                    'token' => $token,
                    'counter' => 0,
                    'sms_time' => null,
                    'sms_code' => null,
                    'status' => 'accept'
                ]);

                $user = AppUser::where('id', $user_id)
                ->select('token', 'fullname', 'avatar', 'phone', 'status')
                ->first();
            
                return $this->sendResponse($user, true, "");
            }
        }else {
            $user = AppUser::where('phone', $request->phone)->first();

            if ($user) {
                $user_id = $user->id;
                $count = $user->counter;
                $i = $count + 1;

                $user = AppUser::where('id', $user_id)->update([
                    'counter' => $i
                ]);

                if ($count == 3) {

                    $user = AppUser::where('id', $user_id)->update([
                        'sms_code' => null,
                        'counter' => 0,
                        'sms_time' => null,
                        'token' => null,
                    ]);

                    return $this->sendResponse(null, false, "The operation is complete, resend the number");

                }

                return $this->sendResponse(null, false, "Wrong Code"); 
            }

            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function login(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $user = AppUser::where('phone', $request->phone)->first();

        if ($user == null) {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }else {
            if(Hash::check($request->password, $user->password) === FALSE){
                return $this->sendResponse(null, false, "Wrong Password");
            }else {
                $token = Str::random(30);
        
                $user_id = $user->id;
                

                $user = AppUser::where('id', $user_id)->update([
                    'token' => $token,
                ]);

                $user = AppUser::where('id', $user_id)
                ->select('token', 'fullname', 'avatar', 'phone', 'status')
                ->first();
            
                return $this->sendResponse($user, true, "");
            }
        }
    }

    public function updateAvatar(Request $request){
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
        
        if ($user == null) {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }else {
            $user_id = $user->id;
            $image_types = array("image/jpeg","image/jpg","image/png");
            $avatar = $request->file('avatar');
            
            if ($avatar) {
                $avatar_type = $avatar->getMimeType();
                if (!in_array($avatar_type, $image_types)) {
                    return $this->sendResponse(null, false, "Please upload image file");
                }

                if ($user->avatar != null) {
                    $user = AppUser::findOrFail($user_id);
                    $old_avatar = $user->avatar;
                    unlink($old_avatar);
                }
                $image_name = Str::random(20);
                $ext = strtolower($avatar->getClientOriginalExtension()); // You can use also getClientOriginalName()
                $image_full_name = $image_name.'.'.$ext;
                $upload_path = 'upload/user/avatar/';    //Creating Sub directory in Public folder to put image
                $save_url_avatar = $upload_path.$image_full_name;
                $success = $avatar->move($upload_path,$image_full_name);

                AppUser::where('id', $user_id)->update([
                    'avatar' => $save_url_avatar,
                ]);

                return $this->sendResponse(null, true, "Update avatar");
            }else {
                return $this->sendResponse(null, false, "Please upload image");
            }

        }
    }

    public function resetPassword(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $user = AppUser::where('phone', $request->phone)->where('sms_code', $request->sms_code)->first();
        $message = $request->sms_code;

        if ($user && !empty($message)) {
            if ($user->status == 'blocked') {
                return $this->sendResponse(null, false, "User blocked", 1);
            }
            $user_id = $user->id;
            $sms_time = $user->sms_time;

            
            $now_time = Carbon::now();
            $date1 = new DateTime($sms_time);
            $date2 = new DateTime($now_time);
            $difference = $date1->diff($date2);
            // $diffInSeconds = $difference->s == 0;
            $diffInMinutes = $difference->i  >= 5;

            if ($diffInMinutes) {

                $user = AppUser::where('id', $user_id)->update([
                    'sms_code' => null,
                    'counter' => 0,
                    'sms_time' => null,
                    'token' => null,
                ]);

                return $this->sendResponse(null, false, "Time Out");
            }

            if (strlen($request->password) < 6) {
                return $this->sendResponse(null, false, "Password length min 6 character");
            }else {
                $token = Str::random(30);
        
                $user = AppUser::where('id', $user_id)->update([
                    'password' => Hash::make($request->password),
                    'token' => $token,
                    'counter' => 0,
                    'sms_time' => null,
                    'sms_code' => null,
                    'status' => 'confirmed'
                ]);

                $user = AppUser::where('id', $user_id)
                ->select('token', 'fullname', 'avatar', 'phone', 'status')
                ->first();
            
                return $this->sendResponse($user, true, "");
            }
        }else {
            $user = AppUser::where('phone', $request->phone)->first();

            if ($user) {
                $user_id = $user->id;
                $count = $user->counter;
                $i = $count + 1;

                $user = AppUser::where('id', $user_id)->update([
                    'counter' => $i
                ]);

                if ($count == 3) {

                    $user = AppUser::where('id', $user_id)->update([
                        'sms_code' => null,
                        'counter' => 0,
                        'sms_time' => null,
                        'token' => null,
                    ]);

                    return $this->sendResponse(null, false, "The operation is complete, resend the number");

                }

                return $this->sendResponse(null, false, "Wrong Code"); 
            }

            return $this->sendResponse(null, false, "Not Found User", 1);
        }
    }

    public function updateProfile(Request $request){
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
            $user = AppUser::where('id', $user_id)->where('avatar', null)
            ->select('token', 'fullname', 'avatar', 'phone', 'status')
            ->first();
            
            $image_types = array("image/jpeg","image/jpg","image/png");
            
            
            if ($request->file('avatar')) {
                $avatar_type = $request->file('avatar')->getMimeType();
                if (!in_array($avatar_type, $image_types)) {
                    return $this->sendResponse(null, false, "Please upload image file");
                }
            }
            if ($request->fullname == null) {
                return $this->sendResponse(null, false, "Please input name");
                    
            }
            $avatar = $request->file('avatar');
            $name = $request->fullname;
            
            if($avatar || $name){
                if ($avatar || $name) {
                
                    if ($user && $avatar) {
    
                        $image_name = Str::random(20);
                        $ext = strtolower($avatar->getClientOriginalExtension()); // You can use also getClientOriginalName()
                        $image_full_name = $image_name.'.'.$ext;
                        $upload_path = 'upload/user/avatar/';    //Creating Sub directory in Public folder to put image
                        $save_url_avatar = $upload_path.$image_full_name;
                        $success = $avatar->move($upload_path,$image_full_name);
    
                        AppUser::where('id', $user_id)->update([
                            'avatar' => $save_url_avatar,
                            'fullname' => $name,
                        ]);

                        $user = AppUser::where('id', $user_id)
                        ->select('token', 'fullname', 'avatar', 'phone', 'status')
                        ->first();
    
                        return $this->sendResponse($user, true, "Insert avatar and name");
                    }elseif ($avatar && $name) {
                        $user = AppUser::findOrFail($user_id);
                        $old_avatar = $user->avatar;
                        unlink($old_avatar);
    
                        $image_name = Str::random(20);
                        $ext = strtolower($avatar->getClientOriginalExtension()); // You can use also getClientOriginalName()
                        $image_full_name = $image_name.'.'.$ext;
                        $upload_path = 'upload/user/avatar/';    //Creating Sub directory in Public folder to put image
                        $save_url_avatar = $upload_path.$image_full_name;
                        $success = $avatar->move($upload_path,$image_full_name);
    
                        AppUser::where('id', $user_id)->update([
                            'avatar' => $save_url_avatar,
                            'fullname' => $name,
                        ]);
                        $user = AppUser::where('id', $user_id)
                        ->select('token', 'fullname', 'avatar', 'phone', 'status')
                        ->first();
                        return $this->sendResponse($user, true, "Updated avatar and name");
                    }else {
                        AppUser::where('id', $user_id)->update([
                            'fullname' => $name,
                        ]);
                        $user = AppUser::where('id', $user_id)
                        ->select('token', 'fullname', 'avatar', 'phone', 'status')
                        ->first();
                        return $this->sendResponse($user, true, "Updated name");
                    }
                    
    
                }else {
                    AppUser::where('id', $user_id)->update([
                        'fullname' => $name,
                    ]);
                    $user = AppUser::where('id', $user_id)
                    ->select('token', 'fullname', 'avatar', 'phone', 'status')
                    ->first();
                    return $this->sendResponse($user, true, "Updated name");
                }
            }
            
            return $this->sendResponse($user, true, "");

        }else {
            return $this->sendResponse(null, false, "Not Found User", 1);
        }

        
    }

    public function sendConfirmCode(Request $request){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $user = AppUser::where('phone', $request->phone)->first();
        $now_time = Carbon::now();
        // $message = rand(1000,9999);
        $message = 1111;

        if ($user == null) {

            AppUser::insert([
                'phone' => $request->phone,
                'status' => 'waiting',
                'created_at' => Carbon::now()
            ]);
            $user = AppUser::where('phone', $request->phone)->first();
            $user_id = $user->id;
            
            // $client = new GuzzleHttp\Client();
            // $res = $client->request('POST', 'http://notify.eskiz.uz/api/message/sms/send',  [
            //     'headers'  => [
            //         'authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9ub3RpZnkuZXNraXoudXpcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MzQ5NzI5ODQsImV4cCI6MTYzNzU2NDk4NCwibmJmIjoxNjM0OTcyOTg0LCJqdGkiOiJMQW5hUkw3V3NsaHN2RkhhIiwic3ViIjo5MywicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.4qBLGzp5bH4siK7VDMRrFE1ejn9z-oeCqrbvIY-TXP4',
            //     ],
            //     'form_params' => [
            //         'mobile_phone' => $request->phone,
            //         'message' => $message,
            //         'from' => 4546,
            //         'callback_url' => 'http://0000.uz/test.php'
            //     ]
            // ]);

            AppUser::where('id', $user_id)->update([
                'sms_code' => $message,
                'counter' => 0,
                'sms_time' => $now_time,
            ]);
            $response = [
                'isReg' => false
            ];
            return $this->sendResponse($response, true, "New User");
        }elseif ($user->status == 'blocked') {
            return $this->sendResponse(null, false, "User blocked", 1);
        }else{
            $user_id = $user->id;
            
            // $client = new GuzzleHttp\Client();
            // $res = $client->request('POST', 'http://notify.eskiz.uz/api/message/sms/send',  [
            //     'headers'  => [
            //         'authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9ub3RpZnkuZXNraXoudXpcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MzQ5NzI5ODQsImV4cCI6MTYzNzU2NDk4NCwibmJmIjoxNjM0OTcyOTg0LCJqdGkiOiJMQW5hUkw3V3NsaHN2RkhhIiwic3ViIjo5MywicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.4qBLGzp5bH4siK7VDMRrFE1ejn9z-oeCqrbvIY-TXP4',
            //     ],
            //     'form_params' => [
            //         'mobile_phone' => $request->phone,
            //         'message' => $message,
            //         'from' => 4546,
            //         'callback_url' => 'http://0000.uz/test.php'
            //     ]
            // ]);

            AppUser::where('id', $user_id)->update([
                'sms_code' => $message,
                'counter' => 0,
                'sms_time' => $now_time,
                'token' => null,
            ]);
            return $this->sendResponse(null, true, "Send Sms");
        }
        
    }
}



