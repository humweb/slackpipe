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

    /**
     * @param string $provider
     *
     * @return string|bool
     */
    static public function hasCustomConfig($provider = '')
    {
        $provider = ucfirst($provider);
        $klass    = 'Humweb\\SlackPipe\\Providers\\'.ucfirst($provider).'\\'.$provider.'Config';

        return class_exists($klass) ? $klass : false;
    }

    /**
     * @param string $provider
     *
     * @return string|bool
     */
    static public function hasCustomSetup($provider)
    {
        $klass = 'Humweb\\SlackPipe\\Providers\\'.ucfirst($provider).'\\SetupCommand';

        return class_exists($klass) ? $klass : false;
    }
}