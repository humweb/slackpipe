<?php

namespace Humweb\SlackPipe;

;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigRemoveCommand extends Command
{
    protected function configure()
    {
        $this->setName('config:remove')->setDescription('Set a token for a provider.')->addArgument('provider', InputArgument::REQUIRED, 'Provider of token.');
    }

    public function providerHasCustomSetup($provider)
    {
        return class_exists($this->providerNamespace($provider, 'SetupCommand'));
    }

    public function providerNamespace($provider, $class = '')
    {
        return '\\Humweb\\SlackPipe\\Providers\\'.ucfirst($provider).'\\'.$class;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $provider = $input->getArgument('provider');

        $baseDir = $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe';

        // Custom Handler
        if ($this->providerHasCustomSetup($provider)) {
            $class   = $this->providerNamespace($provider, 'SetupCommand');
            $command = new $class($this);

            if (method_exists($command, 'remove')) {
                $command->remove($input, $output, $baseDir);
            }
        } else {

            $configPath = $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe'.DIRECTORY_SEPARATOR.'.'.$provider;
            if (file_exists($configPath)) {
                unlink($configPath);
                $output->writeln('Config removed: '.$configPath);
            } else {
                $output->writeln('Config not found.');
            }
        }
    }
}
