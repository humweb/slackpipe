<?php

namespace Humweb\SlackPipe\Providers\Slack;

use CL\Slack\Payload\ChatPostMessagePayload;
use CL\Slack\Payload\FilesUploadPayload;
use CL\Slack\Transport\ApiClient;
use Humweb\SlackPipe\BaseResponse;
use Humweb\SlackPipe\Providers\AbstractProvider;
use Humweb\SlackPipe\Support\Utils;
use Humweb\SlackPipe\Support\Asserts;

/**
 * SlackProvider
 *
 * @package Humweb\SlackPipe
 */
class Provider extends AbstractProvider
{

    public function post()
    {
        $content = $this->readInput();
        $client  = new ApiClient($this->token);
        $payload = new ChatPostMessagePayload();

        $payload->setChannel($this->options->get('channel', '#general'));
        $payload->setUsername($this->options->get('user', 'SlackPipe Bot'));

        if ( ! Asserts::isEmbedUrl($content) && ! Asserts::isImage($content)) {
            $payload->setText("```".PHP_EOL.$content.'```');
        } else {
            $payload->setUnfurlLinks(true);
            $payload->setText($content);
        }

        return $this->getResponse($client->send($payload));
    }

    public function upload()
    {
        $client  = new ApiClient($this->token);
        $payload = new FilesUploadPayload();

        if ($this->options->has('file')) {
            $filename = $this->getFileName($this->options->get('file'));
            $type     = Utils::parseFileExtension($filename);
            $content  = $this->readFile($filename);
        } else {
            $filename = $this->options->get('filename');
            $type     = $this->options->get('type', Utils::parseFileExtension($filename));

            if (is_null($filename)) {
                $filename = Utils::rand().'.'.$type;
            }

            $content = $this->readInput();
        }

        if ($this->options->has('filename')) {
            $filename = $this->options->get('filename');
        }

        if ($this->options->has('title')) {
            $payload->setTitle($this->options->get('title'));
        }

        $payload->setFileType($type);
        $payload->setChannels([$this->options->get('channel')]);
        $payload->setContent($content);
        $payload->setFilename($filename);

        return $this->getResponse($client->send($payload));
    }

    public function getResponse($response)
    {
        $internalResponse = new BaseResponse($this->options);

        if ($response->isOk()) {
            $internalResponse->setOk('Successfully sent to: '.$this->options->get('channel'));
        } else {
            $internalResponse->setFail('Failed to send: '.$response->getErrorExplanation());
        }

        return $internalResponse;
    }

}