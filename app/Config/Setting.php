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

                'beBot' => [
                    'channelToken' => getenv('LINEBOT_BE_CHANNEL_TOKEN'),
                    'channelSecret' => getenv('LINEBOT_BE_CHANNEL_SECRET'),
                ],
            		
            	'feBot' => [
            		'channelToken' => getenv('LINEBOT_FE_CHANNEL_TOKEN'),
            		'channelSecret' => getenv('LINEBOT_FE_CHANNEL_SECRET'),
            	],

                'apiEndpointBase' => getenv('LINEBOT_API_ENDPOINT_BASE'),
            ],
        ];
    }
}
