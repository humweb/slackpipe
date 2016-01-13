<?php

namespace Humweb\SlackPipe\Support\Contracts;

/**
 * ConfigInterface
 *
 * @package Humweb\SlackPipe\Support\Contracts
 */
interface ConfigInterface
{

    /**
     * Constructor
     *
     * @param $provider
     */
    public function __construct($provider);

    /**
     * Get full path of config file
     *
     * @return string
     */
    public function filePath();

    /**
     * Check if config file exists
     *
     * @return bool
     */
    public function exists();

    /**
     * Write data to config file
     *
     * @param array $data
     *
     * @return bool
     */
    public function write($data = []);

    /**
     * Read data from config file
     *
     * @return array
     */
    public function read();

    /**
     * Dehydrate data for storage
     *
     * @param array $data
     *
     * @return array
     */
    public function dehydrate($data = []);

    /**
     * Hydrate data from storage
     *
     * @param array $data
     *
     * @return array
     */
    public function hydrate($data = []);

}