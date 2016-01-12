<?php

namespace Humweb\SlackPipe\Support;

/**
 * Assertions
 *
 * @package Humweb\SlackPipe
 */
class Asserts
{
    static public function isImage($url)
    {
        return preg_match("/\\.(bmp|jpeg|gif|png|jpg)/i", $url);
    }

    static public function isEmbedUrl($url)
    {
        return (strpos($url, 'youtube.com') !== false) || (strpos($url, 'youtu.be') !== false) || (strpos($url, 'twitter.') !== false) || (strpos($url,
                'flickr.') !== false) || (strpos($url, 'xkcd.') !== false);
    }
}