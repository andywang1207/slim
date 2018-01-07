<?php
namespace App\EventHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\BeaconDetectionEvent;
use App\EventHandler\EventHandler;

class BeaconEventHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /* @var BeaconDetectionEvent $beaconEvent */
    private $beaconEvent;

    /**
     * BeaconEventHandler constructor.
     * @param LINEBot $bot
     * @param \Monolog\Logger $logger
     * @param BeaconDetectionEvent $beaconEvent
     */
    public function __construct($bot, $logger, BeaconDetectionEvent $beaconEvent)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->beaconEvent = $beaconEvent;
    }

    public function handle()
    {
        $this->bot->replyText(
            $this->beaconEvent->getReplyToken(),
            'Got beacon message ' . $this->beaconEvent->getHwid()
        );
    }
}
