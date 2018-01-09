<?php
namespace App\Service;

use LINE\LINEBot\Event\BeaconDetectionEvent;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\JoinEvent;
use LINE\LINEBot\Event\LeaveEvent;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\Event\UnknownEvent;
use App\EventHandler\BeaconEventHandler;
use App\EventHandler\FollowEventHandler;
use App\EventHandler\JoinEventHandler;
use App\EventHandler\LeaveEventHandler;
use App\EventHandler\MessageHandler\TextMessageHandler;
use App\EventHandler\PostbackEventHandler;
use App\EventHandler\UnfollowEventHandler;
use App\Config\Setting;

use App\EventHandler\EventHandler;

/* use LINE\LINEBot\Event\MessageEvent\AudioMessage;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\UnknownMessage;
use LINE\LINEBot\Event\MessageEvent\VideoMessage;

use App\EventHandler\MessageHandler\AudioMessageHandler;
use App\EventHandler\MessageHandler\ImageMessageHandler;
use App\EventHandler\MessageHandler\LocationMessageHandler;
use App\EventHandler\MessageHandler\StickerMessageHandler;
use App\EventHandler\MessageHandler\VideoMessageHandler; */

class EventService
{

	private $req;
	private $feBot;
	private $beBot;
	private $logger;
	
	public function __construct($req, $feBot, $beBot, $logger)
	{
		$this->req = $req;
		$this->feBot = $feBot;
		$this->beBot = $beBot;
		$this->logger = $logger;
	}
	
	public function hanle($events) {
		$obj = Setting::getSetting();
	
		foreach ($events as $event) {
			/** @var EventHandler $handler */
			$handler = null;
			 
			if ($event instanceof MessageEvent) {
				$handler = $this->messageEventHandle($event);
			} elseif ($event instanceof UnfollowEvent) {
				$handler = new UnfollowEventHandler($this->feBot, $this->logger, $event);
			} elseif ($event instanceof FollowEvent) {
				$handler = new FollowEventHandler($this->feBot, $this->logger, $event);
			} elseif ($event instanceof JoinEvent) {
				$handler = new JoinEventHandler($this->feBot, $this->logger, $event);
			} elseif ($event instanceof LeaveEvent) {
				$handler = new LeaveEventHandler($this->feBot, $this->logger, $event);
			} elseif ($event instanceof PostbackEvent) {
				$handler = new PostbackEventHandler($this->feBot, $this->logger, $event);
			} elseif ($event instanceof BeaconDetectionEvent) {
				$handler = new BeaconEventHandler($this->feBot, $this->logger, $event);
			} elseif ($event instanceof UnknownEvent) {
				$logger->info(sprintf('Unknown message type has come [type: %s]', $event->getType()));
			} else {
				$logger->info(sprintf(
						'Unexpected event type has come, something wrong [class name: %s]',
						get_class($event)
						));
				continue;
			}
			 
			$handler->handle();
		}
	}
	
    public function messageEventHandle($event) {
    	$handler = null;
		if ($event instanceof TextMessage) {
			$handler = new TextMessageHandler($this->feBot, $this->beBot, $this->logger, $this->req, $event);
		} /* elseif ($event instanceof StickerMessage) {
			$handler = new StickerMessageHandler($bot, $logger, $event);
		} elseif ($event instanceof LocationMessage) {
			$handler = new LocationMessageHandler($bot, $logger, $event);
		} elseif ($event instanceof ImageMessage) {
			$handler = new ImageMessageHandler($bot, $logger, $req, $event);
		} elseif ($event instanceof AudioMessage) {
			$handler = new AudioMessageHandler($bot, $logger, $req, $event);
		} elseif ($event instanceof VideoMessage) {
			$handler = new VideoMessageHandler($bot, $logger, $req, $event);
		} elseif ($event instanceof UnknownMessage) {
			$logger->info(sprintf(
					'Unknown message type has come [message type: %s]',
					$event->getMessageType()
					));
		} else {
			// Unexpected behavior (just in case)
			// something wrong if reach here
			$logger->info(sprintf(
					'Unexpected message type has come, something wrong [class name: %s]',
					get_class($event)
					));
			continue;
		}
		  */
		return $handler;
	}
}
