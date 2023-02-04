<?php

// The default SMS Sender ID to use when one is not provided.
use App\Base\Libraries\SMS\Providers\MSG91;
use App\Base\Libraries\SMS\Providers\SMSIndiaHub;
use App\Base\Libraries\SMS\Providers\SMSLog;
use App\Base\Libraries\SMS\Providers\SMSTrap;

$defaultSenderId = 'WE3';

return [

    /*
            |--------------------------------------------------------------------------
            | Default SMS Provider
            |--------------------------------------------------------------------------
            |
            | The default provider to use.
            |
            | Supported: "smsindiahub", "msg91", "trap", "log"
            |
    */

    'default' => env('SMS_PROVIDER', 'log'),

    /*
            |--------------------------------------------------------------------------
            | Max Message Limit
            |--------------------------------------------------------------------------
            |
            | The maximum character limit allowed when sending messages.
            |
            | Number of SMS  |  Max length
            | -------------     ----------
            | 1              |  160
            | 2              |  306
            | 3              |  459
            | 4              |  612
            |
    */

    'message_limit' => 306,

    /*
            |--------------------------------------------------------------------------
            | SMS Providers
            |--------------------------------------------------------------------------
            |
            | The available SMS providers.
            |
    */

    'providers' => [

        'smsindiahub' => [
            'class' => SMSIndiaHub::class,
            'username' => env('SMSINDIAHUB_USERNAME'),
            'password' => env('SMSINDIAHUB_PASSWORD'),
            'sender_id' => env('SMS_SENDER_ID', $defaultSenderId),
        ],

        'msg91' => [
            'class' => MSG91::class,
            'authkey' => env('MSG91_AUTH_KEY'),
            'sender_id' => env('SMS_SENDER_ID', $defaultSenderId),
        ],

        'trap' => [
            'class' => SMSTrap::class,
            'email' => env('SMS_TRAP_EMAIL'),
        ],

        'log' => [
            'class' => SMSLog::class,
        ],

    ],

];
