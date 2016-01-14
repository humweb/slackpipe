<?php

namespace Humweb\SlackPipe\Providers;

use Humweb\SlackPipe\Support\Contracts\ConfigInterface;
use Humweb\SlackPipe\Support\Options;

/**
 * SlackProvider
 *
 * @package Humweb\SlackPipe
 */
abstract class AbstractProvider
{

    protected $config;
    protected $options;

    /**
     * SlackProvider constructor.
     *
     * @param \Humweb\SlackPipe\Support\Contracts\ConfigInterface $config
     * @param \Humweb\SlackPipe\Support\Options                   $options
     */
    public function __construct(ConfigInterface $config, Options $options)
    {
        $this->config  = $config;
        $this->options = $options;
    }

    abstract function getResponse($response);

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
}