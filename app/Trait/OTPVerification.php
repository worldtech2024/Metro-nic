<?php

namespace App\Trait;

use Vonage\Client;
use Vonage\SMS\Message\SMS;
use Vonage\Client\Credentials\Basic;


// class OTPVerification
// {
//     public static function sendMsg($phone, $company, $opt)
//     {
//         $basic = new Basic("e134eab5", "R7zPIPvRK87QZhFR");
//         $client = new Client($basic);
//         $response = $client->sms()->send(
//             new SMS($phone, $company, 'Your OTP Is : ' . $opt)
//         );
//         $message = $response->current();
//         if ($message->getStatus() == 0) {
//             echo "The message was sent successfully\n";
//         } else {
//             echo "The message failed with status: " . $message->getStatus() . "\n";
//         }
//     }
// }