<?php

namespace Humweb\SlackPipe\Support;

/**
 * Utils
 *
 * @package Humweb\SlackPipe
 */
class Utils
{

    /**
     * @param $channel
     *
     * @return string
     */
    protected function formatChannel($channel)
    {
        if ($channel[0] !== '#') {
            $channel = '#'.$channel;

            return $channel;
        }

        return $channel;
    }

    static public function parseFileExtension($filename = null)
    {
        if ( ! is_null($filename) && strpos($filename, '.') !== false) {
            $type = pathinfo($filename, PATHINFO_EXTENSION);
        } else {
            $type = 'txt';
        }

        return $type;
    }

    static public function rand($length = 5, $chars = 'abcdefghijklmnopqrstuvwxyz0123456789_-')
    {
        $filename = '';
        for ($i = 0; $i < $length; $i++) {
            $filename += $chars[mt_rand(0, 36)];
        }

        return md5(time().$filename);
    }
}