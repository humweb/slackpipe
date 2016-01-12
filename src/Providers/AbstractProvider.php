<?php

namespace Humweb\SlackPipe\Providers;

use Humweb\SlackPipe\Support\Options;

/**
 * SlackProvider
 *
 * @package Humweb\SlackPipe
 */
abstract class AbstractProvider
{

    protected $token;
    protected $options;

    /**
     * SlackProvider constructor.
     *
     * @param string                            $token
     * @param \Humweb\SlackPipe\Support\Options $options
     */
    public function __construct($token, Options $options)
    {
        $this->token = $token;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    protected function getFileName($file)
    {
        if (strpos($file, DIRECTORY_SEPARATOR) === false) {
            $file = getcwd().DIRECTORY_SEPARATOR.$file;
        }

        return $file;
    }

    protected function readInput()
    {
        if (0 === ftell(STDIN)) {
            $contents = '';
            while ( ! feof(STDIN)) {
                $contents .= fread(STDIN, 1024);
            }
        } else {
            throw new \RuntimeException("Please pipe content to STDIN.");
        }

        return $contents;
    }

    protected function readFile($file)
    {
        return file_get_contents($file);
    }

    abstract function getResponse($response);
}