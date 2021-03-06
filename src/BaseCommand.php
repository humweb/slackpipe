<?php

namespace Humweb\SlackPipe;

use Humweb\SlackPipe\Support\Config;
use Humweb\SlackPipe\Support\Contracts\ConfigInterface;
use Humweb\SlackPipe\Support\Options;
use Humweb\SlackPipe\Traits\ProgressbarTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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

    /** @var InputInterface $input */
    protected $input;

    /** @var  Options $options */
    protected $options;

    /** @var ConfigInterface $config */
    protected $config;

    protected $response;

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
        $this->setInputOutput($input, $output);
        $this->config = Config::factory($this->provider, true);
        $this->boot();

        $this->output->writeln(PHP_EOL);
        $this->startProgress(null);

        $this->ensureConfigExists();

        $this->setProgressMessage('Creating "'.$this->provider.'" provider instance.');

        $this->providerInstance = $this->createProvider($this->config, $this->options);
        $this->advanceProgress();

        $this->setProgressMessage('Sending data..');

        // Call actual command handler
        $this->setResponse($this->handle($input, $output));
        $this->advanceProgress();

        $this->setProgressMessage('['.$this->provider.'] '.$this->getResponse()->getMessage());
        $this->finishProgress();
    }

    public function ensureConfigExists()
    {
        if ( ! $this->config->exists()) {
            throw new \RuntimeException("Config file found at: ".$this->config->filePath()."\n"."Generate Config with: ./slackpipe config:set ".$this->provider."\n");
        }
    }

    protected function setInputOutput($input, $output)
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

    protected function createProvider($config, $options)
    {
        $provider = '\\Humweb\\SlackPipe\\Providers\\'.ucfirst($this->provider).'\\Provider';

        return new $provider($config, $options);
    }

    protected function boot()
    {
        $this->options = new Options($this->input->getOptions() + $this->input->getArguments());
    }
}