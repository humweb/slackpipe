<?php

namespace Humweb\SlackPipe;

use Humweb\SlackPipe\Support\Options;
use Humweb\SlackPipe\Traits\ProgressbarTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * UploadCommand
 *
 * @package Humweb\SlackPipe
 */
abstract class BaseCommand extends Command
{
    use ProgressbarTrait;

    protected $provider;
    protected $providerInstance;
    protected $token = '';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var InputInterface
     */
    protected $input;
    protected $response;
    protected $options;

    abstract function handle(InputInterface $input, OutputInterface $output);

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    protected function configure()
    {
        $this->addOption('key', 'k', InputOption::VALUE_OPTIONAL, 'Use a different token than the default one.', false)
            ->addOption('config', 'cfg', InputOption::VALUE_NONE, 'Run config setup.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setIO($input, $output);

        $this->boot();

        $this->output->writeln(PHP_EOL);
        $this->startProgress(null);

        $this->ensureTokenIsAvailable();

        $this->setProgressMessage('Creating "'.$this->provider.'" provider instance.');

        $this->providerInstance = $this->createProvider();
        $this->advanceProgress();

        $this->setProgressMessage('Sending data..');


        // Call actual command handler
        $this->setResponse($this->handle($input, $output));
        $this->advanceProgress();

        $this->setProgressMessage('['.$this->provider.'] '.$this->getResponse()->getMessage());
        $this->finishProgress();
    }

    public function getConfigPath()
    {
        return $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.slackpipe'.DIRECTORY_SEPARATOR.'.'.$this->provider;
    }

    public function ensureTokenIsAvailable()
    {
        $configPath = $this->getConfigPath();
        $config     = $this->options->get('config');

        if ($config) {
            $helper   = $this->getHelper('question');
            $question = new Question('Please enter your token: ', false);
            $token    = $helper->ask($this->input, $this->output, $question);

            if ($token && file_put_contents($configPath, $token) !== false) {
                $this->output->writeln('Token written to file: '.$configPath);
            } else {
                $this->output->writeln('Nothing written..');
            }

            return;
        }

        $token = $this->options->get('key');

        if ($token) {
            $this->token = $token;
        } elseif (file_exists($configPath)) {
            $this->token = trim(file_get_contents($configPath));
        } elseif (file_exists($configPath.'.php')) {
//            $this->token = trim(file_get_contents($configPath));
        } else {
            throw new \RuntimeException("No API token specified or file found: ".$this->getConfigPath()."\n".
            "Generate Config with: ./slackpipe config:set ".$this->provider."\n");

        }
    }

    protected function setIO($input, $output)
    {
        $this->input  = $input;
        $this->output = $output;
    }



    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function createProvider()
    {
        $provider = '\\Humweb\\SlackPipe\\Providers\\'.ucfirst($this->provider).'\\Provider';

        return new $provider($this->token, $this->options);
    }

    protected function boot() {
        $this->options = new Options($this->input->getOptions());
    }
}