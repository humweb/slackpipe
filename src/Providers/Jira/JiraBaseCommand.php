<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\BaseCommand;
use JiraRestApi\Issue\IssueService;

/**
 * UploadCommand
 *
 * @package Humweb\SlackPipe
 */
abstract class JiraBaseCommand extends BaseCommand
{

    protected $issueService;

    public function ensureConfigExists()
    {
        $data = [];

        if ($this->config->exists()) {
            $data = $this->config->read();
        }

        if (empty($data['jiraUser']) || empty($data['jiraPassword']) || empty($data['jiraHost'])) {
            throw new \RuntimeException("No API token specified or file found: ".$this->config->filePath()."\n"."Generate Config with: ./slackpipe config:set ".$this->provider."\n");
        }
    }

    protected function configure()
    {
    }
    protected function boot()
    {
        parent::boot();
        $this->issueService = new IssueService($this->config->createConfigObject());
    }
}