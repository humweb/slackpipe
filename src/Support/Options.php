<?php

namespace Humweb\SlackPipe\Support;

/**
 * Class Options
 *
 * @package Humweb\SlackPipe
 */
class Options
{
    protected $options = [];

    /**
     * Options constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function has($key = null)
    {
        return array_key_exists($key, $this->options) && ! is_null($this->options[$key]);
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->options[$key] : $default;
    }

    public function fill($options)
    {
        return $this->options = $options;
    }

    public function set($key, $val = null)
    {
        return $this->options[$key] = $val;
    }

}