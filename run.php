#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Humweb\SlackPipe\ConfigRemoveCommand;
use Humweb\SlackPipe\ConfigSetCommand;
use Humweb\SlackPipe\Providers\Jira\JiraCommentCommand;
use Humweb\SlackPipe\Providers\Jira\JiraUploadCommand;
use Humweb\SlackPipe\Providers\Slack\SlackPostCommand;
use Humweb\SlackPipe\Providers\Slack\SlackUploadCommand;
use Symfony\Component\Console\Application;

$application = new Application();

//==========[ Framework Commands ]
$application->add(new ConfigSetCommand());
$application->add(new ConfigRemoveCommand());

//==========[ Provider Commands ]
$application->add(new SlackPostCommand());
$application->add(new SlackUploadCommand());

$application->add(new JiraCommentCommand());
$application->add(new JiraUploadCommand());

$application->run();