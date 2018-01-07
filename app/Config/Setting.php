<?php
namespace App\Config;
use Dotenv;

class Setting
{
    public static function getSetting()
    {
    	$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
    	$dotenv->load();
    	
        return [
            'settings' => [
                'displayErrorDetails' => true, // set to false in production

                'logger' => [
                    'name' => 'slim-app',
                    'path' => __DIR__ . '/../../logs/app.log',
                ],

                'bot' => [
                    'channelToken' => getenv('LINEBOT_CHANNEL_TOKEN'),
                    'channelSecret' => getenv('LINEBOT_CHANNEL_SECRET'),
                ],

                'apiEndpointBase' => getenv('LINEBOT_API_ENDPOINT_BASE'),
            ],
        ];
    }
}
