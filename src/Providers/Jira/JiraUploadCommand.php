<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class JiraUploadCommand extends JiraBaseCommand
{
    protected $provider = 'jira';

    protected function configure()
    {
        parent::configure();

        $this->setName('jira:upload')
            ->setDescription('Upload or Pipe data as a file to a Jira issue.')
            ->addArgument('issue', InputArgument::REQUIRED, 'Issue id.')
            ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'File to upload.')
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'This creates a file/snippet instead of a normal message.')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'Specify the content type. (txt, png, php)');
    }

    public function handle(InputInterface $input, OutputInterface $output)
    {
        return $this->providerInstance->upload($this->issueService, $input->getArgument('issue'));
    }

}
