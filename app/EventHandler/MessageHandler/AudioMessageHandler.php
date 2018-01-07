<?php
namespace App\EventHandler\MessageHandler;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\AudioMessage;
use App\EventHandler\EventHandler;
use App\EventHandler\MessageHandler\Util\UrlBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;

class AudioMessageHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var \Slim\Http\Request $logger */
    private $req;
    /** @var AudioMessage $audioMessage */
    private $audioMessage;

    /**
     * AudioMessageHandler constructor.
     * @param LINEBot $bot
     * @param \Monolog\Logger $logger
     * @param \Slim\Http\Request $req
     * @param AudioMessage $audioMessage
     */
    public function __construct($bot, $logger, \Slim\Http\Request $req, AudioMessage $audioMessage)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->req = $req;
        $this->audioMessage = $audioMessage;
    }

    public function handle()
    {
        $contentId = $this->audioMessage->getMessageId();
        $audio = $this->bot->getMessageContent($contentId)->getRawBody();

        $tmpfilePath = tempnam($_SERVER['DOCUMENT_ROOT'] . '/static/tmpdir', 'audio-');
        unlink($tmpfilePath);
        $filePath = $tmpfilePath . '.mp4';
        $filename = basename($filePath);

        $fh = fopen($filePath, 'x');
        fwrite($fh, $audio);
        fclose($fh);

        $replyToken = $this->audioMessage->getReplyToken();

        $url = UrlBuilder::buildUrl($this->req, ['static', 'tmpdir', $filename]);

        $resp = $this->bot->replyMessage(
            $replyToken,
            new AudioMessageBuilder($url, 100)
        );
        $this->logger->info($resp->getRawBody());
    }
}
