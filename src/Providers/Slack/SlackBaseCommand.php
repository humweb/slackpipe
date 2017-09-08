<?php

namespace Humweb\SlackPipe\Providers\Slack;

use CL\Slack\Transport\ApiClient;
use Humweb\SlackPipe\BaseCommand;

/**
 * UploadCommand
 *
 * @package Humweb\SlackPipe
 */
abstract class SlackBaseCommand extends BaseCommand
{

    protected $client;

    public function ensureConfigExists()
    {
        // Throw an exception when no token found
        if ( ! $this->config->has('token')) {
            throw new \RuntimeException('Token not found in config.');
        }
    }

    protected function boot() {
        parent::boot();
        $this->client = new ApiClient($this->config->get('token'));
    }
}
