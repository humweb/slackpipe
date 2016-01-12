<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\Support\Crypt;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SetupCommand
{
    private $command;

    /**
     * SetupCommand constructor.
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    public function remove(InputInterface $input, OutputInterface $output, $baseDir = '')
    {
        $config = new JiraConfig('.jira.php');

        if (file_exists($config->file())) {
            unlink($config->file());
            $output->writeln('Config removed: '.$config->file());
        } else {
            $output->writeln('Config not found.');
        }

    }

    public function handle(InputInterface $input, OutputInterface $output, $baseDir = '')
    {
        $config  = new JiraConfig('.jira.php');
        $baseDir = $config->path();

        if ( ! is_dir($baseDir)) {
            mkdir($baseDir);
        }

        $crypt = new Crypt;
//        var_dump($crypt->encrypt('ahhhhh'));die();
        $configPath  = $config->path().'.jira.php';
        $helper      = $this->command->getHelper('question');
        $configArray = [];

        $configArray['jiraHost'] = $helper->ask($input, $output, new Question('Domain (yourdomain.atlassian.net): ', false));
        $configArray['jiraUser'] = $helper->ask($input, $output, new Question('Username: ', false));

        $passwordQuestion = new Question('Password: ', false);
        $passwordQuestion->setHidden(true)->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $passwordQuestion2 = new Question('Confirm Password: ', false);
        $passwordQuestion2->setHidden(true)->setHiddenFallback(false);
        $password2 = $helper->ask($input, $output, $passwordQuestion2);

        if (empty($password) || empty($password2)) {
            throw new \RuntimeException('Password cannot be blank');
        }

        if ($password !== $password2) {
            throw new \RuntimeException('Passwords must match.');
        }

        $configArray['jiraPassword'] = $crypt->encrypt($password);
        $content                  = "<?php\nreturn ".var_export($configArray, true).";";

        if (file_put_contents($configPath, $content) !== false) {
            $output->writeln('Config written to file: '.$configPath);
        } else {
            $output->writeln('Nothing written..');
        }
    }
}
