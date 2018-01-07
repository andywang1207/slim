<?php
namespace App\EventHandler\MessageHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use App\EventHandler\EventHandler;
use App\EventHandler\MessageHandler\Util\UrlBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

class ImageMessageHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var \Slim\Http\Request $logger */
    private $req;
    /** @var ImageMessage $imageMessage */
    private $imageMessage;

    /**
     * ImageMessageHandler constructor.
     * @param LINEBot $bot
     * @param \Monolog\Logger $logger
     * @param \Slim\Http\Request $req
     * @param ImageMessage $imageMessage
     */
    public function __construct($bot, $logger, \Slim\Http\Request $req, ImageMessage $imageMessage)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->req = $req;
        $this->imageMessage = $imageMessage;
    }

    public function handle()
    {
        $contentId = $this->imageMessage->getMessageId();
        $image = $this->bot->getMessageContent($contentId)->getRawBody();

        $tmpfilePath = tempnam($_SERVER['DOCUMENT_ROOT'] . '/static/tmpdir', 'image-');
        unlink($tmpfilePath);
        $filePath = $tmpfilePath . '.jpg';
        $filename = basename($filePath);

        $fh = fopen($filePath, 'x');
        fwrite($fh, $image);
        fclose($fh);

        $replyToken = $this->imageMessage->getReplyToken();

        $url = UrlBuilder::buildUrl($this->req, ['static', 'tmpdir', $filename]);

        // NOTE: You should pass the url of small image to `previewImageUrl`.
        // This sample doesn't treat that.
        $this->bot->replyMessage($replyToken, new ImageMessageBuilder($url, $url));
    }
}
