<?php
namespace App\EventHandler\MessageHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use App\EventHandler\EventHandler;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

class StickerMessageHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var StickerMessage $stickerMessage */
    private $stickerMessage;

    /**
     * StickerMessageHandler constructor.
     * @param LINEBot $bot
     * @param \Monolog\Logger $logger
     * @param StickerMessage $stickerMessage
     */
    public function __construct($bot, $logger, StickerMessage $stickerMessage)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->stickerMessage = $stickerMessage;
    }

    public function handle()
    {
        $replyToken = $this->stickerMessage->getReplyToken();
        $packageId = $this->stickerMessage->getPackageId();
        $stickerId = $this->stickerMessage->getStickerId();
        $this->bot->replyMessage($replyToken, new StickerMessageBuilder($packageId, $stickerId));
    }
}
