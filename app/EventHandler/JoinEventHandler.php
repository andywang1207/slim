<?php
namespace App\EventHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\JoinEvent;
use App\EventHandler\EventHandler;

class JoinEventHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var JoinEvent $joinEvent */
    private $joinEvent;

    /**
     * JoinEventHandler constructor.
     * @param LINEBot $bot
     * @param \Monolog\Logger $logger
     * @param JoinEvent $joinEvent
     */
    public function __construct($bot, $logger, JoinEvent $joinEvent)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->joinEvent = $joinEvent;
    }

    public function handle()
    {
        if ($this->joinEvent->isGroupEvent()) {
            $id = $this->joinEvent->getGroupId();
        } elseif ($this->joinEvent->isRoomEvent()) {
            $id = $this->joinEvent->getRoomId();
        } else {
            $this->logger->error("Unknown event type");
            return;
        }

        $this->bot->replyText(
            $this->joinEvent->getReplyToken(),
            sprintf('Joined %s %s', $this->joinEvent->getType(), $id)
        );
    }
}
