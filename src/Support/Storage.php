<?php

namespace Humweb\SlackPipe\Support;

/**
 * Storage
 *
 * @package Humweb\SlackPipe\Support
 */
class Storage
{
    protected $path;

    /**
     * Storage constructor.
     *
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->path = rtrim($path, '\/').DIRECTORY_SEPARATOR ?: $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe'.DIRECTORY_SEPARATOR;
    }

    public function get($file)
    {
        return file_get_contents($this->path($file));
    }

    public function path($file = '')
    {
        return $this->path.$file;
    }

    public function put($file, $content = '')
    {
        return file_put_contents($this->path($file), $content, null);
    }
}