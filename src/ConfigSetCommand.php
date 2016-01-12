<?php

namespace Humweb\SlackPipe;

;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfigSetCommand extends Command
{
    protected function configure()
    {
        $this->setName('config:set')
            ->setDescription('Set a token for a provider.')
            ->addArgument('provider', InputArgument::REQUIRED, 'Provider of token.');
    }

    public function providerHasCustomSetup($provider)
    {
        return class_exists($this->providerNamespace($provider, 'SetupCommand'));
    }
    public function providerNamespace($provider, $class = '')
    {
        return  '\\Humweb\\SlackPipe\\Providers\\'.ucfirst($provider).'\\'.$class;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $provider = $input->getArgument('provider');

        $baseDir = $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe';

        if (!is_dir($_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe')){
            mkdir($baseDir);
        }

        // Custom Handler
        if ($this->providerHasCustomSetup($provider)) {
            $class = $this->providerNamespace($provider, 'SetupCommand');
            $command = new $class($this);
            $command->handle($input, $output, $baseDir);
        } else {

            $configPath = $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe'.DIRECTORY_SEPARATOR.'.'.$provider;
            $helper     = $this->getHelper('question');
            $question   = new Question('Please enter your token: ', false);
            $token      = $helper->ask($input, $output, $question);

            if ($token && file_put_contents($configPath, $token) !== false) {
                $output->writeln('Token written to file: '.$configPath);
            } else {
                $output->writeln('Nothing written..');
            }
        }
    }
}
