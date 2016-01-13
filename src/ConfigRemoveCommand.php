<?php

namespace Humweb\SlackPipe;

;
use Humweb\SlackPipe\Support\Asserts;
use Humweb\SlackPipe\Support\Contracts\ConfigInterface;
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

        /** @var ConfigInterface $config */
        $config = Config::factory($provider);

        // Custom Handler
        if ($class = Asserts::hasCustomSetup($provider)) {
            $command = new $class($this, $config);

            if (method_exists($command, 'remove')) {
                $command->remove($input, $output);
            }
        } else {

            $configPath = $config->filePath();
            if ($config->exists()) {
                unlink($configPath);
                $output->writeln('Config removed: '.$configPath);
            } else {
                $output->writeln('Config not found.');
            }
        }
    }

}
