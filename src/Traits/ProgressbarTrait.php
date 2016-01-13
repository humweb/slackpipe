<?php

namespace Humweb\SlackPipe\Traits;

use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Progressbar
 *
 * @package Humweb\SlackPipe\Traits
 */
trait ProgressbarTrait
{
    /**
     * @var ProgressBar
     */
    protected $bar;

    protected function startProgress($num = 2)
    {
        if ( ! $this->getOutput()) {
            throw new \Exception('Unable to find console output object.');
        }

        $this->bar = new ProgressBar($this->getOutput(), $num);
        $this->bar->setFormat("%message%\n [%bar%] %percent:3s%% %elapsed% %memory:6s% ");
        $this->bar->start();
    }

    protected function advanceProgress()
    {
        $this->bar->advance();
    }

    protected function setProgressMessage($msg = '')
    {
        $this->bar->setMessage($msg);
    }

    protected function finishProgress()
    {
        $this->bar->finish();
        $this->output->writeln('');
    }
}