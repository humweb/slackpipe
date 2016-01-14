<?php

namespace Humweb\SlackPipe;

;
use Humweb\SlackPipe\Support\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SetupCommand extends Command
{
    protected function configure()
    {
        $this->setName('setup')->setDescription('Setup app for the first time.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = AppConfig::factory('.slackpipe');

        $token = Utils::rand(32);

        if ($config->write(['app_key' => $token]) !== false) {
            $output->writeln('Token written to file: '.$config->filePath());
        } else {
            $output->writeln('Nothing written..');
        }
    }

}
