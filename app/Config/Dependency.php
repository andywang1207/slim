<?php
namespace App\Config;

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class Dependency
{
    public function register(\Slim\App $app)
    {
        $container = $app->getContainer();

        $container['logger'] = function ($c) {
            $settings = $c->get('settings')['logger'];
            $logger = new \Monolog\Logger($settings['name']);
            $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
            $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
            return $logger;
        };

        $container['beBot'] = function ($c) {
            $settings = $c->get('settings');
            $channelSecret = $settings['beBot']['channelSecret'];
            $channelToken = $settings['beBot']['channelToken'];
            $apiEndpointBase = $settings['apiEndpointBase'];
            $bot = new LINEBot(new CurlHTTPClient($channelToken), [
                'channelSecret' => $channelSecret,
                'endpointBase' => $apiEndpointBase, // <= Normally, you can omit this
            ]);
            return $bot;
        };
        
        $container['feBot'] = function ($c) {
        	$settings = $c->get('settings');
        	$channelSecret = $settings['feBot']['channelSecret'];
        	$channelToken = $settings['feBot']['channelToken'];
        	$apiEndpointBase = $settings['apiEndpointBase'];
        	$bot = new LINEBot(new CurlHTTPClient($channelToken), [
        			'channelSecret' => $channelSecret,
        			'endpointBase' => $apiEndpointBase, // <= Normally, you can omit this
        	]);
        	return $bot;
        };
    }
}
