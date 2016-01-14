<?php

namespace Humweb\SlackPipe;

use Humweb\SlackPipe\Support\Config;
use Humweb\SlackPipe\Support\Storage;

/**
 * JiraConfig
 *
 * @package Humweb\SlackPipe\Providers\Jira
 */
class AppConfig extends Config
{

    protected $filename = '.slackpipe';

    /**
     * JiraConfig constructor.
     *
     * @param string $filename
     */
    public function __construct($filename = '')
    {
        parent::__construct();
    }
}
