<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JiraCommentCommand extends JiraBaseCommand
{
    protected $provider = 'jira';

    protected function configure()
    {
        parent::configure();
        $this->setName('jira:post')->setDescription('Pipe data to Jira issue comment.')->addArgument('issue', InputArgument::REQUIRED, 'Issue id.');
    }

    public function handle(InputInterface $input, OutputInterface $output)
    {
        return $this->providerInstance->comment($this->issueService, $input->getArgument('issue'));
    }
}
