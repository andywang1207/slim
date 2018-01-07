<?php
namespace App\EventHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\LeaveEvent;
use App\EventHandler\EventHandler;

class LeaveEventHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var LeaveEvent $leaveEvent */
    private $leaveEvent;

    /**
     * LeaveEventHandler constructor.
     * @param LINEBot $bot
     * @param \Monolog\Logger $logger
     * @param LeaveEvent $leaveEvent
     */
    public function __construct($bot, $logger, LeaveEvent $leaveEvent)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->leaveEvent = $leaveEvent;
    }

    public function handle()
    {
        if ($this->leaveEvent->isGroupEvent()) {
            $id = $this->leaveEvent->getGroupId();
        } elseif ($this->leaveEvent->isRoomEvent()) {
            $id = $this->leaveEvent->getRoomId();
        } else {
            $this->logger->error("Unknown event type");
            return;
        }

        $this->logger->info(sprintf('Left %s %s', $this->leaveEvent->getType(), $id));
    }
}
