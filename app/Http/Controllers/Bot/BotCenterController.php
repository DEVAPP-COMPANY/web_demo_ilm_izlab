<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\AppUser;

class BotCenterController extends Controller
{
    public function bot(){
        define('API_KEY', '5007440943:AAGe-CNAFJDvqPzpzBLWt2RGIlDLh2GHZGk');

        function sendResponse($method, $datas=[]){
            $url = "https://api.telegram.org/bot".API_KEY."/".$method;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);

            $res = curl_exec($ch);
            if (curl_error($ch)) {
                var_dump(curl_error($ch));
            }else {
                return json_decode($res);
            }
        }

        $update = file_get_contents('php://input');
        Log::info($update);
        $update = json_decode($update, false);

        $message = isset($update->callback_query) ? $update->callback_query->message : $update->message;

        $chat_id = $message->chat->id;
        $text = "";
        $phone = "";

        if (isset($message->text)) {
            $text = $message->text;
        }

        if (isset($message->contact)) {
            $phone = $message->contact->phone_number;
        }
        
        
        // $photo = $message->photo;

        
        function typing($ch){
            return sendResponse('sendChatAction', [
                'chat_id' => $ch,
                'action' => 'typing',
            ]);
        }

        function sendMessage($chat_id, $text){
            return $this->sendResponse("sendMessage",[
                'chat_id' => $chat_id,
                'text' => $text
            ]);
        }

        

        

        // functions
        function select($chat_id){
            $res = AppUser::where('user_id', $chat_id)->first();
            if ($res){
                return $res;
            }else{
                return  false;
            }
        }

        function checkPhone($phone){
            $res = AppUser::where('phone', $phone)->first();
            if ($res){
                return $res;
            }else{
                return  false;
            }
        }

        function insert($chat_id){
            $res = AppUser::insert([
                'user_id' => $chat_id,
                'step' => 1,
                'created_at' => Carbon::now()
            ]);
    
            if ($res) return true;
            else return false;
        }

        function update($opt, $chat_id){
            $res = DB::update("update app_users set $opt where user_id = $chat_id");
            if ($res) return true;
            else return false;
        }

        function delete($chat_id){
            $res = AppUser::where('user_id', $chat_id)->first();
            $res = $res->delete();
            if ($res){
                return true;
            }else{
                return  false;
            }
        }

        

        function action($chat_id, $step){
            if ($step == 1) {
                sendResponse('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "🔖 Ismi-sharifingiz *"
                ]);
            }
        }

        // 

        $step = select($chat_id);
        if ($step) {
            $step = $step['step'];
        }else{
             $step = 0;
        }
        
        

        if ($text == "/start") {
            sendResponse('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Assalomu Alaykum!",
                "reply_markup" => json_encode( [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            ['text' => "Vaalaykum Assalom!"]
                        ],
                    ]
                ])
            ]);
        }elseif ($text == "Vaalaykum Assalom!") {
            sendResponse('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "💻 Bizning hizmatlar - 🏫 O'quv Markazlar qidirish va ularni boshqarish imkoniyatlari!",
                "reply_markup" => json_encode( [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            ['text' => "🔎 Markazlarni qidirish"],
                            ['text' => "🔰 Markazlarni boshqarish"],
                        ],
                    ]
                ])
            ]);
        }elseif ($text == "🔎 Markazlarni qidirish") {
            sendResponse('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "🔍 O'quv Markazlar qidirish hizmati kutilmoqda...",
                "reply_markup" => json_encode( [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            ['text' => "🔎 Markazlarni qidirish"],
                            ['text' => "🔰 Markazlarni boshqarish"],
                        ],
                    ]
                ])
            ]);
        }elseif ($text == "🔰 Markazlarni boshqarish") {
            sendResponse('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "📞 Telefon raqamingiz? *\nNomerni yuborish tugmasini bosing!",
                "reply_markup" => json_encode( [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            [
                                'text' => "Nomerni yuborish",
                                "request_contact"=> true
                            ]
                        ],
                    ]
                ])
            ]);
            $check = 1;
        }elseif ($phone != "") {
            $plus = substr($phone,0,1) == '+';
            $phone = substr($phone,1,12);
            $user = checkPhone($phone);
            if ($plus) {
                
                if ($user) {
                    sendResponse('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bo'limni tanlang",
                        "reply_markup" => json_encode( [
                            'resize_keyboard' => true,
                            'keyboard' => [
                                [
                                    ['text' => "Mening O'quv markazlarim"],
                                    ['text' => "Yangi O'quv markaz qo'shish"],
                                ],
                            ]
                        ])
                    ]); 
                }else {
                    sendResponse('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "Siz hali ro'yhatdan o'tmagansiz! Iltimos ro'yhatdan o'tib ma'lumotlaringizni taqdim eting!",
                    ]);
                    sendResponse('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "Biz bilan birgamisiz?",
                        "reply_markup" => json_encode( [
                            'resize_keyboard' => true,
                            'keyboard' => [
                                [
                                    ['text' => "👍 Ha"],
                                    ['text' => "👎 Yo'q"],
                                ],
                            ]
                        ])
                    ]);
                }
                
            }else{
                if ($user) {
                    sendResponse('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bo'limni tanlang",
                        "reply_markup" => json_encode( [
                            'resize_keyboard' => true,
                            'keyboard' => [
                                [
                                    ['text' => "Mening O'quv markazlarim"],
                                    ['text' => "Yangi O'quv markaz qo'shish"],
                                ],
                            ]
                        ])
                    ]); 
                }else {
                    sendResponse('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "Siz hali ro'yhatdan o'tmagansiz! Iltimos ro'yhatdan o'tib ma'lumotlaringizni taqdim eting!",
                    ]);
                    sendResponse('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "Biz bilan birgamisiz?",
                        "reply_markup" => json_encode( [
                            'resize_keyboard' => true,
                            'keyboard' => [
                                [
                                    ['text' => "👍 Ha"],
                                    ['text' => "👎 Yo'q"],
                                ],
                            ]
                        ])
                    ]);
                }
            }
            
        }elseif ($text == "👎 Yo'q") {
            sendResponse('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "E'tiboringiz uchun rahmat! O'quv Markazlar bilan tanishing",
                "reply_markup" => json_encode( [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            ['text' => "🔎 Markazlarni qidirish"],
                        ],
                    ]
                ])
            ]);  
        }elseif ($text == "👍 Ha") {
            // insert($chat_id);
            sendResponse('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Unday bo'lsa biz boshladik! Iltimos ma'lumotlaringizni kiritishda e'tiborli bo'ling!",
            ]);  
            sendResponse('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Izmingizni kiriting *",
            ]);  
        }


    }
}
