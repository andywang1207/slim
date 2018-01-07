<?php
namespace App\EventHandler\MessageHandler\Util;

class UrlBuilder
{
    public static function buildUrl(\Slim\Http\Request $req, array $paths)
    {
        // NOTE: You should configure $baseUri according to your environment
        // Perhaps, it is prefer to use $_SERVER['HTTP_HOST'], $_SERVER['HTTP_X_FORWARDED_HOST'] or etc
        $baseUri = $req->getUri()->getBaseUrl();
        foreach ($paths as $path) {
            $baseUri .= '/' . urlencode($path);
        }
        return $baseUri;
    }
}
