<?php

namespace Humweb\SlackPipe;

;
use Humweb\SlackPipe\Support\Asserts;
use Humweb\SlackPipe\Support\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfigSetCommand extends Command
{
    protected function configure()
    {
        $this->setName('config:set')->setDescription('Set a token for a provider.')->addArgument('provider', InputArgument::REQUIRED, 'Provider of token.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $provider = $input->getArgument('provider');

        $baseDir = $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe';

        if ( ! is_dir($_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe')) {
            mkdir($baseDir);
        }

        $config = Config::factory($provider);

        // Custom Handler
        if ($class = Asserts::hasCustomSetup($provider)) {
            $command = new $class($this, $config);
            $command->handle($input, $output);
        } else {
            $helper   = $this->getHelper('question');
            $question = new Question('Please enter your token: ', false);
            $token    = $helper->ask($input, $output, $question);
            if ($config->write(['token' => $token]) !== false) {
                $output->writeln('Token written to file: '.$config->filePath());
            } else {
                $output->writeln('Nothing written..');
            }
        }
    }

}
