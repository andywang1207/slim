<?php
namespace App\Config;

use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;

use App\Service\EventService;

class Route
{
    public function register(\Slim\App $app)
    {
        $app->post('/events', function (\Slim\Http\Request $req, \Slim\Http\Response $res) {
            /** @var LINEBot $bot */
            $bot = $this->bot;
            /** @var \Monolog\Logger $logger */
            $logger = $this->logger;

            $signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
            if (empty($signature)) {
                $logger->info('Signature is missing');
                return $res->withStatus(400, 'Bad Request');
            }

            try {
                $events = $bot->parseEventRequest($req->getBody(), $signature[0]);
            } catch (InvalidSignatureException $e) {
                $logger->info('Invalid signature');
                return $res->withStatus(400, 'Invalid signature');
            } catch (InvalidEventRequestException $e) {
                return $res->withStatus(400, "Invalid event request");
            }

            $service = new EventService($res, $bot, $logger);
            $service->hanle($events);

            $res->write('OK');
            return $res;
        });
    }
}
