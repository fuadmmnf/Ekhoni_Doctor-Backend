<?php


namespace App\Http\Controllers\Handlers;

use Berkayk\OneSignal\OneSignalFacade;

class PushNotificationHandler
{
    public function sendNotificationToSpecificUser($user, $message)
    {
//
////        [
////            {"id": "like-button", "text": "Like", "icon": "http://i.imgur.com/N8SN8ZS.png", "url": "https://yoursite.com"}, {
////        "id": "read-more-button", "text": "Read more", "icon": "http://i.imgur.com/MIxJp1L.png", "url": "https://yoursite.com"}
////        ]
//
////        $playerIds = json_decode($user->device_ids, true);
////        foreach ($playerIds as $playerId) {
//            OneSignalFacade::async()->sendNotificationToUser(
//                $message,
//                "fb805ef2-2747-4ea6-bf8c-128a32aa5d40",
//                $url = null,
//                $data = array('type' => 'checkup'),
//                $buttons = array(
//                    array(
//                        "id" => "accept-button",
//                        "text" => "receive",
//                        "url" => ""
//                    )
//                ),
//                $schedule = null
//            );
//        }
//
////    }
}
