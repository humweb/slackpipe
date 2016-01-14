<?php

namespace Humweb\SlackPipe\Support;

/**
 * Container
 *
 * @package Humweb\SlackPipe\Support
 */
class Container
{
    protected static $instance;
    protected        $items = [];

    /**
     * Set the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if ( ! (static::$instance instanceof Container)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @return object
     */
    public function get($key)
    {
        return $this->items[$key];
    }

    /**
     * @param $item
     *
     * @return $this
     */
    public function put($key, $item)
    {
        $this->items[$key] = $item;

        return $this;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items[$key]);
    }

}