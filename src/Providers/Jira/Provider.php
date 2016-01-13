<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\BaseResponse;
use Humweb\SlackPipe\Providers\AbstractProvider;
use Humweb\SlackPipe\Support\Utils;
use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

/**
 * SlackProvider
 *
 * @package Humweb\SlackPipe
 */
class Provider extends AbstractProvider
{

    public $name = 'jira';
    public $issue;

    public function comment($issueKey)
    {
        $this->issue = $issueKey;
        $content     = $this->readInput();

        return $this->getResponse($this->postPipe($issueKey, $content));
    }

    public function getResponse($response)
    {
        $internalResponse = new BaseResponse($this->options);

        if ($response instanceof JIRAException) {
            $internalResponse->setFail('Failed to send: '.$response->getMessage());
        } elseif ($response == true && empty($response->errorMessages)) {
            $internalResponse->setOk('Successfully sent to: '.$this->issue);
        } else {
            $internalResponse->setFail('Failed to send: '.implode(PHP_EOL, $response->errorMessages));
        }

        return $internalResponse;
    }

    public function postPipe($issueKey, $body)
    {
        try {
            $comment = new Comment();

            $comment->setBody($body)->setVisibility('role', 'Users');

            $issueService = new IssueService($this->getConfig());
            $resp         = $issueService->addComment($issueKey, $comment);
        } catch (JIRAException $e) {
            //            var_dump($e, $e instanceof JIRAException);
            //            exit();
            //            exit();
            return $e;
        }
        if ( ! empty($resp->errorMessages)) {
            return $resp;
        }

        return true;
    }

    public function getConfig()
    {
        $config = new JiraConfig('jira');
        if ( ! $config->exists()) {
        }

        return $config->createConfigObject();
    }

    public function upload($issueKey)
    {
        $this->issue = $issueKey;
        if ($this->options->has('file')) {
            $filename = $this->getFileName($this->options->get('file'));
            $response = $this->uploadFile($issueKey, $filename);
        } else {
            $filename = $this->options->get('filename');
            $type     = $this->options->get('type');
            $content  = $this->readInput();
            $self     = $this;
            $response = $this->createTempUploadFile($filename, $content, $type, function ($file) use ($issueKey, $self) {
                return $self->uploadFile($issueKey, $file);
            });
        }

        return $this->getResponse($response);
    }

    public function uploadFile($issueKey, $file)
    {

        try {
            $issueService = new IssueService($this->getConfig());

            // multiple file upload support.
            $ret = $issueService->addAttachments($issueKey, [$file]);
        } catch (JIRAException $e) {
            return $e;
        }

        if ( ! empty($resp->errorMessages)) {
            return $resp;
        }

        return true;
    }

    protected function createTempUploadFile($filename, $data, $type = null, $cb)
    {
        $filename = ! empty($filename) ? '-'.$filename : '';
        $type     = ! empty($type) ? '.'.$type : '';

        $fn = sys_get_temp_dir().DIRECTORY_SEPARATOR.md5(Utils::rand(3).time()).$filename.$type;

        $handle = fopen($fn, "w");
        fwrite($handle, $data);
        fclose($handle);
        $response = $cb($fn);

        unlink($fn);

        return $response;
    }
}