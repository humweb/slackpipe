<?php
require __DIR__.'/../vendor/autoload.php';

use Humweb\SlackPipe\AppConfig;
use Humweb\SlackPipe\Support\Container;
use Humweb\SlackPipe\Support\Encryption;
use Humweb\SlackPipe\Support\Storage;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Application;

//==========[ Initialize and bind application to container ]
$application = new Application();
Container::getInstance()->put('app', $application);

//==========[ Setup vStream fs ]
Container::getInstance()->put('vfs', $testVFS = vfsStream::setup('test-dir'));

//==========[ Bind components to the container ]
$config = new AppConfig('.slackpipe', true);
Container::getInstance()->put('storage', new Storage(vfsStream::url('test-dir')));
Container::getInstance()->put('config', $config);
Container::getInstance()->put('crypt', new Encryption($config->get('app_key')));

return Container::getInstance();