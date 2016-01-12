<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\Support\Crypt;
use JiraRestApi\Configuration\ArrayConfiguration;

/**
 * JiraConfig
 *
 * @package Humweb\SlackPipe\Providers\Jira
 */
class JiraConfig
{

    protected $filename;

    /**
     * JiraConfig constructor.
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function createConfigObject()
    {
        $crypt                = new Crypt;
        $data                 = include($this->path().$this->filename);
        $data['jiraPassword'] = $crypt->decrypt($data['jiraPassword']);

        return new ArrayConfiguration($data);
    }

    public function path()
    {
        return $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe'.DIRECTORY_SEPARATOR;
    }

    public function file()
    {
        return $this->path().$this->filename;
    }

    public function exists()
    {
        return file_exists($this->path().$this->filename);
    }

    /**
     * @return mixed
     */
    public function filename()
    {
        return $this->filename;
    }

}
