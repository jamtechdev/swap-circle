<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class PushNotificationController extends Controller{

    /* --------------- PUSH NOTIFICATION --------------- */
    public function push_notification($oneSignalDeviceId, $type, $message){
        $oneSignalAppId    = DB::table('system_settings')->where('type','oneSignal_app_id')->first()->description; 
        $oneSignalApiKey   = DB::table('system_settings')->where('type','onesignal_api_key')->first()->description; 

        $fields = [
            'app_id'             => $oneSignalAppId, 
            'include_player_ids' => [$oneSignalDeviceId],
            'headings'           => ['en' => $type],     
            'contents'           => ["en" => $message]    
        ];
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            "Authorization: Basic {$oneSignalApiKey}"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    /* --------------- PUSH NOTIFICATION --------------- */
}