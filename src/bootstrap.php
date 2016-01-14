<?php
require __DIR__.'/../vendor/autoload.php';

use Humweb\SlackPipe\AppConfig;
use Humweb\SlackPipe\Support\Container;
use Humweb\SlackPipe\Support\Encryption;
use Humweb\SlackPipe\Support\Storage;
use Symfony\Component\Console\Application;

//==========[ Initialize and bind application to container ]
$application = new Application();
Container::getInstance()->put('app', $application);

//==========[ Bind components to the container ]
Container::getInstance()->put('storage', new Storage($_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe'.DIRECTORY_SEPARATOR));

$config = new AppConfig('.slackpipe', true);
if ( ! $config->exists()) {
    throw new RuntimeException('App config not set.');
}
Container::getInstance()->put('config', $config);
Container::getInstance()->put('crypt', new Encryption($config->get('app_key')));

return Container::getInstance();