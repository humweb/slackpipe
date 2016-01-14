<?php

namespace Humweb\SlackPipe\Providers\Slack;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SlackPostCommand extends SlackBaseCommand
{
    protected $provider = 'slack';

    protected function configure()
    {
        parent::configure();
        $this->setName('slack:post')
            ->setDescription('Pipe data to slack channel.')
            ->addOption('channel', 'c', InputOption::VALUE_REQUIRED, 'What channel to send the message to.', '#general')
            ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'Post as user.', 'SlackPipe Bot');
    }

    public function handle(InputInterface $input, OutputInterface $output)
    {
        return $this->providerInstance->post($this->client);
    }
}
