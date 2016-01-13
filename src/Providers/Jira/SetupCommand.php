<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\Support\Contracts\ConfigInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SetupCommand
{
    private $command;
    /**
     * @var \Humweb\SlackPipe\Support\Contracts\ConfigInterface
     */
    private $config;

    /**
     * SetupCommand constructor.
     *
     * @param                                                     $command
     * @param \Humweb\SlackPipe\Support\Contracts\ConfigInterface $config
     */
    public function __construct($command, ConfigInterface $config)
    {
        $this->command = $command;
        $this->config  = $config;
    }

    public function remove(InputInterface $input, OutputInterface $output)
    {

        if (file_exists($this->config->file())) {
            unlink($this->config->filePath());
            $output->writeln('Config removed: '.$this->config->filePath());
        } else {
            $output->writeln('Config not found.');
        }
    }

    public function handle(InputInterface $input, OutputInterface $output)
    {

        $data   = [];
        $helper = $this->command->getHelper('question');

        $data['jiraHost'] = $helper->ask($input, $output, new Question('Domain (yourdomain.atlassian.net): ', false));
        $data['jiraUser'] = $helper->ask($input, $output, new Question('Username: ', false));

        $passwordQuestion = new Question('Password: ', false);
        $passwordQuestion->setHidden(true)->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQuestion);

        //        $passwordQuestion2 = new Question('Confirm Password: ', false);
        //        $passwordQuestion2->setHidden(true)->setHiddenFallback(false);
        //        $password2 = $helper->ask($input, $output, $passwordQuestion2);

        //        if (empty($password) || empty($password2)) {
        //            throw new \RuntimeException('Password cannot be blank');
        //        }
        //
        //        if ($password !== $password2) {
        //            throw new \RuntimeException('Passwords must match.');
        //        }

        $data['jiraPassword'] = $password;

        if ($this->config->write($data) !== false) {
            $output->writeln('Config written to file: '.$this->config->filePath());
        } else {
            $output->writeln('Nothing written..');
        }
    }
}
