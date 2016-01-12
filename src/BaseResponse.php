<?php

namespace Humweb\SlackPipe;

use Humweb\SlackPipe\Support\Options;

/**
 * BaseResponse
 *
 * @package Humweb\SlackPipe
 */
class BaseResponse
{

    public    $status  = false;
    public    $message = '';
    protected $options;

    /**
     * BaseResponse constructor.
     *
     * @param \Humweb\SlackPipe\Support\Options $options
     */
    public function __construct(Options $options = null)
    {
        $this->options = $options;
    }

    public function isOk()
    {
        return $this->status;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setOk($message)
    {
        $this->status  = true;
        $this->message = $message;
    }

    public function setFail($message)
    {
        $this->status  = false;
        $this->message = $message;
    }
}