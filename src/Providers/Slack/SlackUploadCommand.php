<?php

namespace Humweb\SlackPipe\Providers\Slack;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SlackUploadCommand extends SlackBaseCommand
{
    protected $provider = 'slack';

    protected function configure()
    {
        parent::configure();

        $this->setName('slack:upload')
            ->setDescription('Upload or Pipe data as a file to a slack channel.')
            ->addOption('channel', 'c', InputOption::VALUE_REQUIRED, 'What channel to send the message to.', '#general')
            ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'File to upload.')
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'This creates a file/snippet instead of a normal message.')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'Specify the content type. (txt, png, php)')
            ->addOption('title', null, InputOption::VALUE_OPTIONAL, 'Optional title');
    }

    public function handle(InputInterface $input, OutputInterface $output)
    {
        return $this->providerInstance->upload($this->client);
    }

}
