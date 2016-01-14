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


    /**
     * @param $issueService
     * @param $issueKey
     *
     * @return \Humweb\SlackPipe\BaseResponse
     */
    public function comment($issueService, $issueKey)
    {
        $this->issue = $issueKey;
        $content     = $this->readInput();

        return $this->getResponse($this->postPipe($issueService, $issueKey, $content));
    }


    /**
     * @param $response
     *
     * @return \Humweb\SlackPipe\BaseResponse
     */
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


    /**
     * @param $issueService
     * @param $issueKey
     * @param $body
     *
     * @return bool|\Exception|\JiraRestApi\JiraException
     */
    public function postPipe($issueService, $issueKey, $body)
    {
        try {
            $comment = new Comment();
            $comment->setBody($body)->setVisibility('role', 'Users');
            $resp = $issueService->addComment($issueKey, $comment);
        } catch (JIRAException $e) {
            return $e;
        }
        if ( ! empty($resp->errorMessages)) {
            return $resp;
        }

        return true;
    }


    /**
     * @param $service
     * @param $issueKey
     *
     * @return \Humweb\SlackPipe\BaseResponse
     */
    public function upload($service, $issueKey)
    {
        $this->issue = $issueKey;
        if ($this->options->has('file')) {
            $filename = $this->getFileName($this->options->get('file'));
            $response = $this->uploadFile($service, $issueKey, $filename);
        } else {
            $filename = $this->options->get('filename');
            $type     = $this->options->get('type');
            $content  = $this->readInput();
            $self     = $this;

            // Create temporary file and upload it
            $response = $this->createTempUploadFile($filename, $content, $type, function ($file) use ($service, $issueKey, $self) {
                return $self->uploadFile($service, $issueKey, $file);
            });
        }

        return $this->getResponse($response);
    }


    /**
     * @param $issueService
     * @param $issueKey
     * @param $file
     *
     * @return bool|\Exception|\JiraRestApi\JiraException
     */
    public function uploadFile($issueService, $issueKey, $file)
    {

        try {
            // multiple file upload support.
            $resp = $issueService->addAttachments($issueKey, [$file]);
        } catch (JIRAException $e) {
            return $e;
        }

        if ( ! empty($resp->errorMessages)) {
            return $resp;
        }

        return true;
    }


    /**
     * @param      $filename
     * @param      $data
     * @param null $type
     * @param      $cb
     *
     * @return mixed
     */
    protected function createTempUploadFile($filename, $data, $type = null, $cb)
    {
        $filename = ! empty($filename) ? '-'.$filename : '';
        $type     = ! empty($type) ? '.'.$type : '';

        // Assemble temporary file path string
        $fn = sys_get_temp_dir().DIRECTORY_SEPARATOR.md5(Utils::rand(3).time()).$filename.$type;

        // Open file
        $handle = fopen($fn, "w");

        // Write file
        fwrite($handle, $data);
        fclose($handle);

        // Pass file to callback
        $response = $cb($fn);

        // Remove file
        unlink($fn);

        return $response;
    }
}